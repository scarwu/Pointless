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

use Whoops\Run as WhoopsRun;
use Whoops\Handler\PlainTextHandler as WhoopsPlainTextHandler;
use Whoops\Handler\PrettyPageHandler as WhoopsPrettyPageHandler;

// Set Error Reporting
if ('production' === APP_ENV) {
    error_reporting(0);
} else {
    error_reporting(E_ALL);

    // Register Whoops Exception Handler
    $whoops = new WhoopsRun();

    if ('cli' === PHP_SAPI) {
        $whoops->pushHandler(new WhoopsPlainTextHandler());
    } else {
        $whoops->pushHandler(new WhoopsPrettyPageHandler());
    }

    $whoops->register();
}

use Oni\Core\Loader;

// Loader Append
Loader::append('Pointless\Library', APP_ROOT . '/libraries');
Loader::append('Pointless\Extend', APP_ROOT . '/extends');
Loader::append('Pointless\Format', APP_ROOT . '/formats');

use Pointless\Library\Utility;
use Pointless\Library\Resource;

if ('cli' === PHP_SAPI) {

    // Define Variables
    define('HOME_ROOT', $_SERVER['HOME'] . '/.pointless5');

    // Create Home Folder & Fix Permission
    Utility::mkdir(HOME_ROOT);
    Utility::fixPermission(HOME_ROOT);
}

// Require Constant Attr
require APP_ROOT . '/constant.php';

// Set Resource
Resource::set('system:constant', $constant);

use Oni\CLI\App as CLIApp;
use Oni\Web\App as WebApp;

// New App Instance
if ('cli' === PHP_SAPI) {
    $app = new CLIApp();
    $app->setAttr('task/namespace', 'Pointless\Task');
    $app->setAttr('task/path', APP_ROOT . '/tasks');
    $app->setAttr('task/default/handler', 'Intro');
} else {
    $app = new WebApp();
    $app->setAttr('controller/namespace', 'Pointless\Controller');
    $app->setAttr('controller/path', APP_ROOT . '/controllers');
    $app->setAttr('controller/default/Handler', 'Main');
    $app->setAttr('controller/default/action', 'index');
    $app->setAttr('controller/error/Handler', 'Main');
    $app->setAttr('controller/error/action', 'index');
}

$app->run();
