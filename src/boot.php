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
define('BUILD_VERSION', $constant['build']['version']);
define('BUILD_TIMESTAMP', $constant['build']['timestamp']);

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

    // Get Timestamp
    $timestamp = file_exists(APP_HOME . '/.timestamp')
        ? file_get_contents(APP_HOME . '/.timestamp') : 0;

    // Check Timestamp
    if (BUILD_TIMESTAMP !== $timestamp) {

        // Remove Old Sample
        Utility::remove(APP_HOME . '/sample');

        // Copy New Sample
        Utility::copy(APP_ROOT . '/sample', APP_HOME . '/sample');

        // Update Timestamp
        file_put_contents(APP_HOME . '/timestamp', BUILD_TIMESTAMP);
    }
}

// Change Owner (Fix Permission)
if (IS_SUPER_USER) {
    Utility::chown(APP_HOME, fileowner($_SERVER['HOME']), filegroup($_SERVER['HOME']));
}

// Init Pointless Commnad
(new Pointless\Command\MainCommand)->init();
