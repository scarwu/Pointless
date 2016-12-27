<?php
/**
 * Bootstrap
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2016, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

// Set default timezone
date_default_timezone_set('Etc/UTC');

// Require Constant
require APP_ROOT . '/constant.php';

// Define Variables
define('APP_HOME', $_SERVER['HOME'] . '/.pointless3');

define('BUILD_VERSION', $constant['build']['version']);
define('BUILD_TIMESTAMP', $constant['build']['timestamp']);

define('CORE_EXT', APP_ROOT . '/extends');
define('CORE_LIB', APP_ROOT . '/libraries');

define('IS_SUPER_USER', isset($_SERVER['SUDO_USER']));

// Require Extends
// require CORE_EXT . '/LimitedCommand.php';
// require CORE_EXT . '/PostCommand.php';
// require CORE_EXT . '/Doctype.php';
// require CORE_EXT . '/ThemeTools.php';
// require CORE_EXT . '/ThemeScript.php';
// require CORE_EXT . '/Extension.php';

// Require Libraries
// require CORE_LIB . '/Utility.php';
// require CORE_LIB . '/Resource.php';
// require CORE_LIB . '/Misc.php';

// Composer Autoloader
require APP_ROOT . '/vendor/autoload.php';

// NanoCLI Loader
NanoCLI\Loader::set('Pointless\Extend', APP_ROOT . '/extends');
NanoCLI\Loader::set('Pointless\Command', APP_ROOT . '/commands');
NanoCLI\Loader::set('Pointless\Doctype', APP_ROOT . '/doctypes');
NanoCLI\Loader::set('Pointless\Library', APP_ROOT . '/libraries');
NanoCLI\Loader::register();

// Initialize Folder & Files
if (!file_exists(APP_HOME)) {
    mkdir(APP_HOME, 0755, true);
}

if ('production' === APP_ENV) {
    if (!file_exists(APP_HOME . '/sample')) {
        mkdir(APP_HOME . '/sample', 0755, true);
    }

    $timestamp = 0;

    if (file_exists(APP_HOME . '/.timestamp')) {
        $timestamp = file_get_contents(APP_HOME . '/.timestamp');
    }

    // Check Timestamp and Update Sample Files
    if (BUILD_TIMESTAMP !== $timestamp) {
        Utility::remove(APP_HOME . '/sample');

        // Copy Sample Files
        Utility::copy(APP_ROOT . '/sample', APP_HOME . '/sample');

        // Create Timestamp File
        file_put_contents(APP_HOME . '/timestamp', BUILD_TIMESTAMP);
    }
}

// Change Owner
if (IS_SUPER_USER) {
    Utility::chown(APP_HOME, fileowner($_SERVER['HOME']), filegroup($_SERVER['HOME']));
}

// Init Pointless Commnad
(new Pointless\Command\MainCommand)->init();
