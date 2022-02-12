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

define('BLOG_ROOT', getenv('BLOG_ROOT'));
define('BLOG_POST', BLOG_ROOT . '/posts');
define('BLOG_ASSET', BLOG_ROOT . '/assets');

if (false !== getenv('BLOG_THEME')) {
    define('BLOG_THEME', getenv('BLOG_THEME'));
} else {
    require  BLOG_ROOT . '/config.php';

    if ('' === $config['theme']) {
        $config['theme'] = 'Classic';
    }

    if (true === file_exists(BLOG_ROOT . "/themes/{$config['theme']}")) {
        define('BLOG_THEME', BLOG_ROOT . "/themes/{$config['theme']}");
    } else {
        define('BLOG_THEME', APP_ROOT . '/sample/themes/Classic');
    }
}

// Loader Append
use Oni\Core\Loader;

Loader::append('Pointless\Handler', BLOG_THEME . '/handlers');
Loader::append('Pointless\Extension', BLOG_THEME . '/extensions');
Loader::append('Pointless\Library', APP_ROOT . '/libraries');
Loader::append('Pointless\Extend', APP_ROOT . '/extends');
Loader::append('Pointless\Format', APP_ROOT . '/formats');
Loader::append('Pointless\Handler', APP_ROOT . '/handlers');
Loader::append('Pointless\Extension', APP_ROOT . '/extensions');

// New Oni Web Application Instance
use Oni\Web\App;

$app = new App();
$app->setAttr('controller/namespace', 'Pointless\Viewer\Controller');
$app->setAttr('controller/path', APP_ROOT . '/controllers');
$app->setAttr('controller/default/Handler', 'Main');
$app->setAttr('controller/default/action', 'index');
$app->setAttr('controller/error/Handler', 'Main');
$app->setAttr('controller/error/action', 'index');
$app->setAttr('view/path', BLOG_THEME . '/views');
$app->run();
