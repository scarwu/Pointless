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

// Fix: PREG_JIT_STACKLIMIT_ERROR (PHP 7)
ini_set('pcre.jit', false);

// Composer Autoloader
require APP_ROOT . '/vendor/autoload.php';

// Set Loader
Oni\CLI\Loader::set('Pointless\Command', APP_ROOT . '/commands');
Oni\CLI\Loader::set('Pointless\Library', APP_ROOT . '/libraries');
Oni\CLI\Loader::set('Pointless\Extend', APP_ROOT . '/extends');
Oni\CLI\Loader::set('Pointless\Format', APP_ROOT . '/formats');

// Loader Register
Oni\CLI\Loader::register();

// Require Constant Attr
require APP_ROOT . '/constant.php';

Pointless\Library\Resource::set('attr:constant', $constant);

// Define Variables
define('BUILD_VERSION', $constant['build']['version']);
define('BUILD_TIMESTAMP', $constant['build']['timestamp']);

// Define Home Root
define('HOME_ROOT', $_SERVER['HOME'] . '/.pointless3');

// Create Folder
Pointless\Library\Utility::mkdir(HOME_ROOT);

if ('production' === APP_ENV) {

    // Create Folder
    Pointless\Library\Utility::mkdir(HOME_ROOT . '/sample');

    // Get Timestamp
    $timestamp = file_exists(HOME_ROOT . '/.timestamp')
        ? file_get_contents(HOME_ROOT . '/.timestamp') : 0;

    // Check Timestamp
    if (BUILD_TIMESTAMP !== $timestamp) {

        // Remove Old Sample
        Pointless\Library\Utility::remove(HOME_ROOT . '/sample');

        // Copy New Sample
        Pointless\Library\Utility::copy(APP_ROOT . '/sample', HOME_ROOT . '/sample');

        // Update Timestamp
        file_put_contents(HOME_ROOT . '/timestamp', BUILD_TIMESTAMP);
    }
}

// Fix Permission
Pointless\Library\Misc::fixPermission(HOME_ROOT);

// Init Pointless Commnad
(new Pointless\Command\MainCommand)->init();
