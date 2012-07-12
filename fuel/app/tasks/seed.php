<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2012 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Tasks;


class Seed
{
    public function __construct()
    {
        \Package::load('sentry');
    }
	/**
	 * This method gets ran when a valid method name is not used in the command.
	 *
	 * Usage (from command line):
	 *
	 * php oil r seed
	 *
	 * @return string
	 */
	public static function run()
	{
        // Prompt the user with menu options
        $option = \Cli::prompt('What would you like to do?', array('sentry_setup','sentry_user'));

        switch($option)
        {
            case "sentry_setup":
                return static::sentry_setup();
                break;
            case "sentry_user":
                return static::sentry_user();
                break;
            default:
                break;
        }
	}

    public static function sentry_setup()
    {
        echo \Cli::write("Create sentry groups", 'blue');

        try
        {
            $group_id = \Sentry::group()->create(array(
                'name'  => 'superadmin',
                'level' => 255,
                'is_admin' => true
            ));

            if(is_numeric($group_id))
            {
                echo \Cli::write("Super admin group created", 'green');
            }
            else
            {
                echo \Cli::write("Error creating super admin group", 'red');
            }

        }
        catch (SentryGroupException $e)
        {
            echo \Cli::write($e->getMessage(), 'red');
        }

        try
        {
            $group_id = \Sentry::group()->create(array(
                'name'  => 'admin',
                'level' => 100,
                'is_admin' => true
            ));

            if(is_numeric($group_id))
            {
                echo \Cli::write("Admin group created", 'green');
            }
            else
            {
                echo \Cli::write("Error creating admin group", 'red');
            }

        }
        catch (SentryGroupException $e)
        {
            echo \Cli::write($e->getMessage(), 'red');
        }
    }

	public static function sentry_user()
	{
        echo \Cli::write("Create admin user", 'blue');

        $user_id = 0;

        $username = \CLI::prompt('Use what username', 'admin');
        $email = \CLI::prompt('Use what email address', 'admin@domain.com');
        $password = \CLI::prompt('Use what password', 'password');
        $first_name = \CLI::prompt('Use what first name', 'System');
        $last_name = \CLI::prompt('Use what last name', 'Administrator');
        $group_id = \CLI::prompt('Which group does user belong to', '1');

        try
        {
            $vars = array(
                'email'    => $email,
                'password' => $password,
                'username' => $username,
                'metadata' => array(
                    'first_name' => $first_name,
                    'last_name'  => $last_name,
                )
            );

            $user_id = \Sentry::user()->create($vars);

            if ($user_id)
            {
                echo \Cli::write("User created", 'green');
            }
            else
            {
                echo \Cli::write("Error creating user", 'red');
            }
        }
        catch (SentryUserException $e)
        {
            echo \Cli::write($e->getMessage(), 'red');
        }

        if($user_id > 0)
        {
            try
            {
                // update the user
                $user = \Sentry::user($user_id);

                // option 1
                $added_to_group = $user->add_to_group($group_id);

                if($added_to_group)
                {
                    echo \Cli::write("User added to group " . $group_id, 'green');
                }
                else
                {
                    echo \Cli::write("Error adding user to group", 'red');
                }
            }
            catch (SentryUserException $e)
            {
                echo \Cli::write($e->getMessage(), 'red');
            }
        }

  	}
}

/* End of file tasks/seed.php */
