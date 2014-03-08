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
define('LIBRARY', ROOT . '/Library');

require LIBRARY . '/Resource.php';
require LIBRARY . '/GeneralFunction.php';

/**
 * Define Path and Initialize Blog
 */
define('HOME', $_SERVER['HOME'] . '/.pointless2');

if (!file_exists(HOME)) {
    mkdir(HOME, 0755, true);
}

// Define Regular Expression Rule
define('REGEX_RULE', '/^({(?:.|\n)*?})\n((?:.|\n)*)/');

/**
 * Copy Sample Files
 */
if (defined('BUILD_TIMESTAMP')) {
    if (!file_exists(HOME . '/Sample')) {
        mkdir(HOME . '/Sample', 0755, true);
    }

    $timestamp = file_exists(HOME . '/Timestamp')
        ? file_get_contents(HOME . '/Timestamp')
        : 0;

    // Check Timestamp and Update Sample Files
    if (BUILD_TIMESTAMP !== $timestamp) {
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
 * Composer Autoloader and Set NanoCLI
 */
require ROOT . '/../vendor/autoload.php';

NanoCLI\Loader::set('Pointless', ROOT . '/Command');
NanoCLI\Loader::register();

// Run Pointless Command
$pointless = new Pointless();
$pointless->init();
