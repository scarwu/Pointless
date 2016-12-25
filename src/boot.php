<?php
/**
 * Bootstrap
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

// Set default timezone
date_default_timezone_set('Etc/UTC');

// Define Core Path
define('POI_EXT', ROOT . '/extends');
define('POI_LIB', ROOT . '/libraries');

// Require Extends
// require POI_EXT . '/LimitedCommand.php';
// require POI_EXT . '/PostCommand.php';
require POI_EXT . '/Doctype.php';
require POI_EXT . '/ThemeTools.php';
require POI_EXT . '/ThemeScript.php';
require POI_EXT . '/Extension.php';

// Require Libraries
require POI_LIB . '/Utility.php';
require POI_LIB . '/Resource.php';
require POI_LIB . '/GeneralFunction.php';

// Composer Autoloader
require ROOT . '/vender/autoload.php';

// NanoCLI Command Loader
NanoCLI\Loader::set('Pointless', ROOT . '/commands');
NanoCLI\Loader::register();

// Require Constants
require ROOT . '/constants.php';

define('BUILD_VERSION', $constants['build']['version']);
define('BUILD_TIMESTAMP', $constants['build']['timestamp']);

/**
 * Define Path and Initialize Blog
 */
define('HOME', $_SERVER['HOME'] . '/.pointless3');

if (!file_exists(HOME)) {
    mkdir(HOME, 0755, true);
}

/**
 * Copy Sample Files
 */
if ('production' === ENV) {
    if (!file_exists(HOME . '/sample')) {
        mkdir(HOME . '/sample', 0755, true);
    }

    $timestamp = 0;
    if (file_exists(HOME . '/Timestamp')) {
        $timestamp = file_get_contents(HOME . '/Timestamp');
    }

    // Check Timestamp and Update Sample Files
    if (BUILD_TIMESTAMP !== $timestamp) {
        Utility::remove(HOME . '/sample');

        // Copy Sample Files
        Utility::copy(ROOT . '/sample', HOME . '/sample');

        // Create Timestamp File
        file_put_contents(HOME . '/timestamp', BUILD_TIMESTAMP);
    }
}

// Change Owner
if (isset($_SERVER['SUDO_USER'])) {
    Utility::chown(HOME, fileowner($_SERVER['HOME']), filegroup($_SERVER['HOME']));
}

// Run Pointless Command
$pointless = new Pointless();
$pointless->init();
