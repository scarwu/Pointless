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

/**
 * Path Define and Copy Files
 */
define('VENDOR', ROOT . '/Vendor');
define('LIBRARY', ROOT . '/Library');

require LIBRARY . '/Resource.php';
require LIBRARY . '/GeneralFunction.php';

/**
 * Define Path and Initialize Blog
 */
define('HOME', $_SERVER['HOME'] . '/.pointless2');

if(!file_exists(HOME))
    mkdir(HOME, 0755, TRUE);

define('BLOG', HOME . '/Blog');

if(!file_exists(BLOG))
    mkdir(BLOG, 0755, TRUE);

if(!file_exists(BLOG . '/Config.php'))
    copy(ROOT . '/Sample/Config.php', BLOG . '/Config.php');

// Require Config
require BLOG . '/Config.php';

$config['blog_url'] = $config['blog_dn'] . $config['blog_base'];
Resource::set('config', $config);

/**
 * Temp
 */
define('TEMP', BLOG . '/Temp');

if(!file_exists(TEMP)) {
    mkdir(TEMP, 0755, TRUE);
}

/**
 * Deploy
 */
define('DEPLOY', BLOG . '/Deploy');

if(!file_exists(DEPLOY)) {
    mkdir(DEPLOY, 0755, TRUE);
}

/**
 * Markdown
 */
define('MARKDOWN', BLOG . '/Markdown');

if(!file_exists(MARKDOWN)) {
    mkdir(MARKDOWN, 0755, TRUE);
    recursiveCopy(ROOT . '/Sample/Markdown', MARKDOWN);
}

/**
 * Theme
 */
if(!file_exists(BLOG . '/Theme')) {
    mkdir(BLOG . '/Theme', 0755, TRUE);
    recursiveCopy(ROOT . '/Sample/Theme', BLOG . '/Theme');
}

if('' == $config['blog_theme'])
    $config['blog_theme'] = 'Classic';

if(file_exists(BLOG . "/Theme/{$config['blog_theme']}"))
    define('THEME', BLOG . "/Theme/{$config['blog_theme']}");
else
    define('THEME', ROOT . '/Sample/Theme/Classic');

/**
 * Extension
 */
define('EXTENSION', BLOG . '/Extension');

if(!file_exists(EXTENSION)) {
    mkdir(EXTENSION, 0755, TRUE);
}

/**
 * Resource
 */
define('RESOURCE', BLOG . '/Resource');

if(!file_exists(RESOURCE))
    mkdir(RESOURCE, 0755, TRUE);

// Set Time Zone
date_default_timezone_set($config['timezone']);

// Define Regular Expression Rule
define('REGEX_RULE', '/^({(?:.|\n)*?})\n((?:.|\n)*)/');

/**
 * Copy Sample Files
 */
if(defined('BUILD_TIMESTAMP')) {
    if(!file_exists(HOME . '/Sample'))
        mkdir(HOME . '/Sample', 0755, TRUE);

    $timestamp = file_exists(HOME . '/Timestamp')
        ? file_get_contents(HOME . '/Timestamp')
        : 0;

    // Check Timestamp and Update Sample Files
    if(BUILD_TIMESTAMP != $timestamp) {
        recursiveRemove(HOME . '/Sample');
        
        // Copy Sample Files
        recursiveCopy(ROOT . '/Sample', HOME . '/Sample');
        copy(LIBRARY . '/Route.php', HOME . '/Sample/Route.php');

        // Create Timestamp File
        $handle = fopen(HOME . '/Timestamp', 'w+');
        fwrite($handle, BUILD_TIMESTAMP);
        fclose($handle);
    }
}

/**
 * Load NanoCLI and Setting
 */
require VENDOR . '/NanoCLI/src/NanoCLI/Loader.php';

NanoCLI\Loader::register('NanoCLI', VENDOR . '/NanoCLI/src');
NanoCLI\Loader::register('Pointless', ROOT . '/Command');

spl_autoload_register('NanoCLI\Loader::load');

/**
 * Load Twig Template Engine
 */
require VENDOR . '/Twig/lib/Twig/Autoloader.php';

Twig_Autoloader::register();

// Run Pointless Command
$pointless = new Pointless();
$pointless->init();