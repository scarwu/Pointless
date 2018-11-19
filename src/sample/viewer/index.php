<?php
/**
 * Bootstrap
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

// Set Default Time Zone
date_default_timezone_set('Etc/UTC');

// Fix: PREG_JIT_STACKLIMIT_ERROR (PHP 7)
ini_set('pcre.jit', false);

// Define Global Constants
define('ROOT', __DIR__ . '/..');
define('POI_ROOT', ROOT . '/../subModules/Pointless/src');

if (is_dir(getenv('POI_BLOG_PATH'))) {
    define('BLOG_POST', getenv('POI_BLOG_PATH') . '/posts');
    define('BLOG_STATIC', getenv('POI_BLOG_PATH') . '/static');
} else {
    define('BLOG_POST', POI_ROOT . '/sample/posts');
    define('BLOG_STATIC', null);
}

// Require Composer Autoloader
require ROOT . '/application/vendor/autoload.php';

error_reporting(E_ALL);

// Register Whoops Exception Handler
$whoops = new Whoops\Run();
$whoops->pushHandler(new Whoops\Handler\PrettyPageHandler());
$whoops->register();

// New Oni Web Application Instance
$app = new Oni\Web\App();

$app->setAttr('controller/namespace', 'WebApp\Controller');
$app->setAttr('controller/path', ROOT . '/application/controllers');
$app->setAttr('controller/default/Handler', 'Main');
$app->setAttr('controller/default/action', 'index');
$app->setAttr('controller/error/Handler', 'Main');
$app->setAttr('controller/error/action', 'index');
$app->setAttr('model/namespace', 'WebApp\Model');
$app->setAttr('model/path', ROOT . '/application/models');
$app->setAttr('view/path', ROOT . '/application/views');

// Loader Append
Oni\Loader::append('Pointless\Handler', ROOT . '/application/handlers');
Oni\Loader::append('Pointless\Extension', ROOT . '/application/extensions');
Oni\Loader::append('Pointless\Library', POI_ROOT . '/libraries');
Oni\Loader::append('Pointless\Extend', POI_ROOT . '/extends');
Oni\Loader::append('Pointless\Format', POI_ROOT . '/formats');
Oni\Loader::append('Pointless\Handler', POI_ROOT . '/sample/handlers');
Oni\Loader::append('Pointless\Extension', POI_ROOT . '/sample/extensions');

// Start Application
$app->run();
