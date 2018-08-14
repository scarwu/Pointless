<?php
/**
 * Bootstrap
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

// Set default timezone
date_default_timezone_set('Etc/UTC');

// Fix: PREG_JIT_STACKLIMIT_ERROR (PHP 7)
ini_set('pcre.jit', false);

// Composer Autoloader
require APP_ROOT . '/vendor/autoload.php';

use Oni\Loader;
use Oni\CLI\App;

// New Oni CLI Application Instance
$app = new App();

// Set Attr
$app->setAttr('task/namespace', 'Pointless\Task');
$app->setAttr('task/path', APP_ROOT . '/tasks');
$app->setAttr('task/default/handler', 'Intro');

// Loader Append
Loader::append('Pointless\Library', APP_ROOT . '/libraries');
Loader::append('Pointless\Extend', APP_ROOT . '/extends');
Loader::append('Pointless\Format', APP_ROOT . '/formats');

use Pointless\Library\Resource;
use Pointless\Library\Utility;
use Pointless\Library\Misc;

// Require Constant Attr
require APP_ROOT . '/constant.php';

Resource::set('system:constant', $constant);

// Define Variables
define('BUILD_VERSION', $constant['build']['version']);
define('BUILD_TIMESTAMP', $constant['build']['timestamp']);

// Define Home Root
define('HOME_ROOT', $_SERVER['HOME'] . '/.pointless3');

// Create Folder
Utility::mkdir(HOME_ROOT);

if ('production' === APP_ENV) {

    // Create Folder
    Utility::mkdir(HOME_ROOT . '/sample');

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

// Fix Permission
Misc::fixPermission(HOME_ROOT);

// Run Application
$app->run();
