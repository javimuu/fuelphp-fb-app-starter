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
        echo \Cli::write('Options:');
        echo \Cli::write('Create Sentry Group [sg]:');
        echo \Cli::write('Create Sentry User [su]:');
        echo \Cli::write('Exit [q]:');

        $option = \Cli::prompt('What would you like to do?');

        switch($option)
        {
            case "sentry_group":
            case "sg":
                return static::sentry_group();
                break;
            case "sentry_user":
            case "su":
                return static::sentry_user();
                break;
            case "q":
            default:
                exit;
                break;
        }
    }

    public static function sentry_group()
    {
        echo \Cli::write("Create sentry group", 'blue');

        // get group information
        $groups = array();

        try
        {
            $groups = \Sentry::group()->all();
        }
        catch (\SentryGroupException $e)
        {
            $errors = $e->getMessage();
        }

        if(count($groups) > 0)
        {
            $group_str = '';
            foreach($groups as $group)
            {
                $group_str .= "\r\n" . $group['id'] . ' - ' . $group['name'];
            }
            $continue = \CLI::prompt('These groups already exist:' . $group_str . "\r\nContinue?", 'y');
            if($continue != 'y') exit;
        }

        $name = \CLI::prompt('Enter group name', 'admin');
        $level = \CLI::prompt('Enter group level', '100');
        $is_admin = \CLI::prompt('Is the group an admin', '1');

        try
        {
            $group_id = \Sentry::group()->create(array(
                'name'  => $name,
                'level' => $level,
                'is_admin' => $is_admin
            ));

            if(is_numeric($group_id))
            {
                echo \Cli::write("Group created", 'green');
            }
            else
            {
                echo \Cli::write("Error creating group", 'red');
            }
        }
        catch (\SentryGroupException $e)
        {
            echo \Cli::write($e->getMessage(), 'red');
        }

        return static::run();
    }

    public static function sentry_user()
    {
        try
        {
            $groups = \Sentry::group()->all();
        }
        catch (\SentryGroupException $e)
        {
            $errors = $e->getMessage();
        }

        if(!count($groups) > 0)
        {
            echo \Cli::write("Please create some groups first", 'red');
            return static::sentry_group();
        }
        else
        {
            $group_str = '';
            foreach($groups as $group)
            {
                $group_str .= "\r\n" . $group['id'] . ' - ' . $group['name'];
            }
        }

        echo \Cli::write("Create user", 'blue');

        $user_id = 0;

        $username = \CLI::prompt('Enter username', 'admin');
        $email = \CLI::prompt('Enter email address', 'admin@domain.com');
        $password = \CLI::prompt('Enter password', 'password');
        $first_name = \CLI::prompt('Enter first name', 'System');
        $last_name = \CLI::prompt('Enter last name', 'Administrator');
        $group_id = \CLI::prompt('Which group does user belong to:' . $group_str . "\r\n", '1');

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
        catch (\SentryUserException $e)
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
            catch (\SentryUserException $e)
            {
                echo \Cli::write($e->getMessage(), 'red');
            }
        }

        return static::run();

    }
}

/* End of file tasks/seed.php */
