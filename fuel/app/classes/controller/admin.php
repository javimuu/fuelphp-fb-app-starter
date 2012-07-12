<?php

class Controller_Admin extends Controller_Base {

    protected $current_user = null;
    protected $exclude_methods = array('login', 'reset_password', 'reset_password_confirm', 'activate', 'register');

    public $template = 'templates/admin';

    public function before()
    {
        parent::before();

        if(Sentry::check())
        {
            $this->current_user = Sentry::user()->get('metadata');

            // get groups user belongs to and assign to view, to save DB calls from views
            $users_groups = array();
            foreach(Sentry::user()->groups() as $group)
            {
                $users_groups[] = $group['name'];
            }
            View::set_global('users_groups', $users_groups);
        }

        // Set a global variable so views can use it
        View::set_global('current_user', $this->current_user);

        if ( !Sentry::user()->is_admin() and !in_array(Request::active()->action, $this->exclude_methods))
        {
            Response::redirect('admin/login');
        }
    }

    public function action_login()
    {
        // Already logged in
        Sentry::check() and Response::redirect('admin');

        $val = Validation::forge();

        if (Input::method() == 'POST')
        {
            $val->add('login', ucfirst(Config::get('sentry.login_column')))
                ->add_rule('required');
            $val->add('password', 'Password')
                ->add_rule('required');

            if ($val->run())
            {
                try
                {
                    if (Sentry::check() or Sentry::login(Input::post('login'), Input::post('password'), false))
                    {
                        // credentials ok, go right in
                        $this->current_user = Sentry::user()->get('metadata');
                        Session::set_flash('info', 'Welcome, '.$this->current_user['first_name'] . ' ' . $this->current_user['last_name']);
                        Response::redirect('admin');
                    }
                    else
                    {
                        Session::set_flash('error', 'Login/Password are incorrect');
                    }
                }
                catch (SentryAuthException $e)
                {
                    Session::set_flash('error', $e->getMessage());
                }
            }
        }

        $this->template->title = 'Login';
        $this->template->content = View::forge('admin/auth/login', array('val' => $val));
    }

    public function action_register()
    {
        // check registration allowed
        if(!Config::get('sentry.registration_allowed'))
        {
            Session::set_flash('error', 'Registration isn\'t allowed');
            Response::redirect('admin/login');
        }

        $val = Validation::forge();

        if (Input::method() == 'POST')
        {
            // get login column
            $login_column = Config::get('sentry.login_column');

            if($login_column == 'username')
            {
                $val->add('login', 'Login')
                ->add_rule('required');
            }
            $val->add('email', 'Email')
                ->add_rule('required')
                ->add_rule('valid_email');
            $val->add('password', 'Password')
                ->add_rule('required');
            $val->add('confirm_password', 'Confirm Password')
                ->add_rule('required')
                ->add_rule('match_field[password]');

            if ($val->run())
            {
                try
                {
                    $activation_reqired = Config::get('sentry.activation_reqired');

                    $user_data = array(
                        'email' => Input::post('email'),
                        'password' => Input::post('password')
                    );

                    // username
                    if($login_column == 'username') $user_data['username'] = Input::post('login');

                    // Meta data
                    if(!is_null(Input::post('first_name'))) $user_data['metadata']['first_name'] = Input::post('first_name');
                    if(!is_null(Input::post('last_name'))) $user_data['metadata']['last_name'] = Input::post('last_name');

                    if($user = Sentry::user()->create($user_data, $activation_reqired)) // register is a alias of create with 2nd param of true
                    {
                        if($activation_reqired)
                        {
                            $link = Uri::create('admin/activate/' . $user['hash']);

                            Session::set_flash('success', 'Account activation email has been sent');
                            Session::set_flash('info', $link);
                        }
                        else
                        {
                            Session::set_flash('success', 'Account created');
                            Response::redirect('admin/login');
                        }
                    }
                    else
                    {
                        Session::set_flash('error', 'Login/Password are incorrect');
                    }
                }
                catch (SentryAuthException $e)
                {
                    Session::set_flash('error', $e->getMessage());
                }
            }
        }

        $this->template->title = 'Register';
        $this->template->content = View::forge('admin/auth/register', array('val' => $val));
    }

    public function action_activate($email = null, $hash = null)
    {
        try
        {
            if ($reset = Sentry::activate_user($email, $hash))
            {
                Session::set_flash('success', 'Account activated');
                Response::redirect('admin/login');
            }
            else
            {
                Session::set_flash('error', 'Account not activated');
                Response::redirect('admin/register');
            }
        }
        catch (SentryAuthException $e)
        {
            Session::set_flash('error', $e->getMessage());
            Response::redirect('admin/register');
        }
    }

    public function action_reset_password()
    {
        $val = Validation::forge();

        if (Input::method() == 'POST')
        {
            $val->add('login', 'Login')
                ->add_rule('required');
            $val->add('password', 'Password')
                ->add_rule('required');

            if ($val->run())
            {
                try
                {
                    if ($reset = Sentry::reset_password(Input::post('login'), Input::post('password')))
                    {
                        $email = $reset['email'];
                        $link = Uri::create('admin/reset_password_confirm/' . $reset['link']);

                        Session::set_flash('success', 'Password reset email has been sent');
                        Session::set_flash('info', $link);

                        // email $link to $email
                    }
                    else
                    {
                        Session::set_flash('error', 'An error occurred, please try again');
                    }
                }
                catch (SentryAuthException $e)
                {
                    Session::set_flash('error', $e->getMessage());
                }
            }
        }

        $this->template->title = 'Reset Password';
        $this->template->content = View::forge('admin/auth/reset_password', array('val' => $val));
    }

    public function action_reset_password_confirm($email = null, $hash = null)
    {
        try
        {
            if ($reset = Sentry::reset_password_confirm($email, $hash))
            {
                Session::set_flash('success', 'The password was reset');
                Response::redirect('admin/login');
            }
            else
            {
                Session::set_flash('error', 'The password was not reset');
                Response::redirect('admin/reset_password');
            }
        }
        catch (SentryAuthException $e)
        {
            Session::set_flash('error', $e->getMessage());
            Response::redirect('admin/reset_password');
        }
    }

    /**
     * The logout action.
     *
     * @access  public
     * @return  void
     */
    public function action_logout()
    {
        Sentry::logout();
        Response::redirect('admin');
    }

    /**
     * The index action.
     *
     * @access  public
     * @return  void
     */
    public function action_index()
    {
        $this->template->title = 'Dashboard';
        $this->template->content = View::forge('admin/dashboard');
    }

}

/* End of file admin.php */