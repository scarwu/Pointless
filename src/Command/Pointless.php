<?php
/**
 * Pointless Command
 *
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

use NanoCLI\Command;

class Pointless extends Command
{
    public function __construct()
    {
        parent::__construct();

        // Set default timezone
        date_default_timezone_set('Etc/UTC');

        /**
         * Path Define and Copy Files
         */
        define('LIBRARY', ROOT . '/Library');

        require LIBRARY . '/Utility.php';
        require LIBRARY . '/Resource.php';
        require LIBRARY . '/GeneralFunction.php';

        /**
         * Define Path and Initialize Blog
         */
        define('HOME', $_SERVER['HOME'] . '/.pointless2');

        if (!file_exists(HOME)) {
            mkdir(HOME, 0755, true);
        }

        // Define Regular Expression Rule
        define('REGEX_RULE', '/^({(?:.|\n)*?})\n((?:.|\n)*)/');

        /**
         * Copy Sample Files
         */
        if (defined('BUILD_TIMESTAMP')) {
            if (!file_exists(HOME . '/Sample')) {
                mkdir(HOME . '/Sample', 0755, true);
            }

            $timestamp = 0;
            if (file_exists(HOME . '/Timestamp')) {
                $timestamp = file_get_contents(HOME . '/Timestamp');
            }

            // Check Timestamp and Update Sample Files
            if (BUILD_TIMESTAMP !== $timestamp) {
                Utility::remove(HOME . '/Sample');

                // Copy Sample Files
                Utility::copy(ROOT . '/Sample', HOME . '/Sample');
                copy(LIBRARY . '/Route.php', HOME . '/Sample/Route.php');

                // Create Timestamp File
                file_put_contents(HOME . '/Timestamp', BUILD_TIMESTAMP);
            }
        }

        // Change Owner
        if (isset($_SERVER['SUDO_USER'])) {
            Utility::chown(HOME, fileowner($_SERVER['HOME']), filegroup($_SERVER['HOME']));
        }
    }

    public function run()
    {
        $help = new Pointless\HelpCommand();
        $help->run();
    }
}
