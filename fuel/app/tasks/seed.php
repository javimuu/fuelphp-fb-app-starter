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
        return \Cli::write("Create admin user oil r seed:sentry", 'blue');
	}

	public static function sentry()
	{
        \Package::load('sentry');

        echo \Cli::write("Create admin user", 'blue');

        $username = \CLI::prompt('Use what username', 'admin');
        $email = \CLI::prompt('Use what email address', 'admin@domain.com');
        $password = \CLI::prompt('Use what password', 'password');
        $first_name = \CLI::prompt('Use what first name', 'System');
        $last_name = \CLI::prompt('Use what last name', 'Administrator');

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
            echo \Cli::write($e->getMessage(), 'red');; // catch errors such as user exists or bad fields
        }
  	}
}

/* End of file tasks/seed.php */
