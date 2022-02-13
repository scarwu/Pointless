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

// Require Composer Autoloader
require APP_ROOT . '/vendor/autoload.php';

// Set Error Reporting
if ('production' === APP_ENV) {
    error_reporting(0);
} else {
    error_reporting(E_ALL);

    // Register Whoops Exception Handler
    $whoops = new Whoops\Run();
    $whoops->pushHandler(new \Whoops\Handler\PlainTextHandler());
    $whoops->register();
}

// Require Constant Attr
require APP_ROOT . '/constant.php';

// Define Variables
define('BUILD_VERSION', $constant['build']['version']);
define('BUILD_TIMESTAMP', $constant['build']['timestamp']);

// Define Home Root
define('HOME_ROOT', $_SERVER['HOME'] . '/.pointless5');

// Loader Append
use Oni\Core\Loader;

Loader::append('Pointless\Library', APP_ROOT . '/libraries');
Loader::append('Pointless\Extend', APP_ROOT . '/extends');
Loader::append('Pointless\Format', APP_ROOT . '/formats');

// Load Pointless Classes
use Pointless\Library\Utility;
use Pointless\Library\Resource;

// Create Home Folder & Fix Permission
Utility::mkdir(HOME_ROOT);
Utility::fixPermission(HOME_ROOT);

// Set Resource
Resource::set('system:constant', $constant);

// New Oni CLI Application Instance
use Oni\CLI\App;

$app = new App();
$app->setAttr('task/namespace', 'Pointless\Task');
$app->setAttr('task/path', APP_ROOT . '/tasks');
$app->setAttr('task/default/handler', 'Intro');
$app->run();
