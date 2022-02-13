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
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
    $whoops->register();
}

// Require Constant Attr
require APP_ROOT . '/constant.php';

// Loader Append
use Oni\Core\Loader;

Loader::append('Pointless\Library', APP_ROOT . '/libraries');
Loader::append('Pointless\Extend', APP_ROOT . '/extends');
Loader::append('Pointless\Format', APP_ROOT . '/formats');

// Load Pointless Classes
use Pointless\Library\Resource;

// Set Resource
Resource::set('constant', $constant);

// New Oni Web Application Instance
use Oni\Web\App;

$app = new App();
$app->setAttr('controller/namespace', 'Pointless\Viewer\Controller');
$app->setAttr('controller/path', APP_ROOT . '/controllers');
$app->setAttr('controller/default/Handler', 'Main');
$app->setAttr('controller/default/action', 'index');
$app->setAttr('controller/error/Handler', 'Main');
$app->setAttr('controller/error/action', 'index');
$app->run();
