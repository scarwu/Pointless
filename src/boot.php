<?php
/**
 * Bootstrap
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
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

// Define Home Root
define('HOME_ROOT', $_SERVER['HOME'] . '/.pointless3');

// Initialize Folder & Files
if (!file_exists(HOME_ROOT)) {
    mkdir(HOME_ROOT, 0755, true);
}

if ('production' === APP_ENV) {
    if (!file_exists(HOME_ROOT . '/sample')) {
        mkdir(HOME_ROOT . '/sample', 0755, true);
    }

    // Get Timestamp
    $timestamp = file_exists(HOME_ROOT . '/.timestamp')
        ? file_get_contents(HOME_ROOT . '/.timestamp') : 0;

    // Check Timestamp
    if (BUILD_TIMESTAMP !== $timestamp) {

        // Remove Old Sample
        Utility::remove(HOME_ROOT . '/sample');

        // Copy New Sample
        Utility::copy(APP_ROOT . '/sample', HOME_ROOT . '/sample');

        // Update Timestamp
        file_put_contents(HOME_ROOT . '/timestamp', BUILD_TIMESTAMP);
    }
}

// Fix Folder Permission
Pointless\Library\Misc::fixFolerPermission(HOME_ROOT);

// Init Pointless Commnad
(new Pointless\Command\MainCommand)->init();
