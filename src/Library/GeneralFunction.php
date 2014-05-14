<?php
/**
 * General Function
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

/**
 * Check Default Blog
 */
function checkDefaultBlog()
{
    $msg = "Default blog is't set.\nPlease use command \"home set\" or \"home init\".";

    if (!file_exists(HOME . '/Default')) {
        IO::error($msg);

        return false;
    }

    $path = file_get_contents(HOME . '/Default');

    if ('' === $path) {
        IO::error($msg);

        return false;
    }

    if (!file_exists($path) || !file_exists("$path/.pointless")) {
        file_put_contents(HOME . '/defualt', '');

        IO::error($msg);

        return false;
    }

    define('BLOG', $path);

    return true;
}

/**
 * Initialize Blog
 */
function initBlog()
{
    if (!file_exists(BLOG . '/.pointless')) {
        file_put_contents(BLOG . '/.pointless', '');
    }

    if (!file_exists(BLOG . '/Config.php')) {
        copy(ROOT . '/Sample/Config.php', BLOG . '/Config.php');
    }

    // Require Config
    require BLOG . '/Config.php';
    Resource::set('config', $config);

    // Define Path
    define('TEMP', BLOG . '/Temp');
    define('DEPLOY', BLOG . '/Deploy');
    define('RESOURCE', BLOG . '/Resource');
    define('EXTENSION', BLOG . '/Extension');
    define('MARKDOWN', BLOG . '/Markdown');

    if (!file_exists(TEMP)) {
        mkdir(TEMP, 0755);
    }

    if (!file_exists(DEPLOY)) {
        mkdir(DEPLOY, 0755);
    }

    if (!file_exists(RESOURCE)) {
        mkdir(RESOURCE, 0755);
    }

    if (!file_exists(EXTENSION)) {
        mkdir(EXTENSION, 0755);
    }

    if (!file_exists(MARKDOWN)) {
        Utility::copy(ROOT . '/Sample/Markdown', MARKDOWN);
    }

    if (!file_exists(BLOG . '/Theme')) {
        Utility::copy(ROOT . '/Sample/Theme', BLOG . '/Theme');
    }

    if ('' === $config['theme']) {
        $config['theme'] = 'Classic';
    }

    if (file_exists(BLOG . "/Theme/{$config['theme']}")) {
        define('THEME', BLOG . "/Theme/{$config['theme']}");
    } else {
        define('THEME', ROOT . '/Sample/Theme/Classic');
    }

    // Set Timezone
    date_default_timezone_set($config['timezone']);

    // Change Owner
    if (isset($_SERVER['SUDO_USER'])) {
        Utility::chown(BLOG, fileowner(HOME), filegroup(HOME));
    }
}

/**
 * Parse Markdown File
 */
function parseMarkdownFile($filename, $skip = false)
{
    // Define Regular Expression Rule
    $regex = '/^(?:<--({(?:.|\n)*})-->)\s*(?:#(.*))?((?:.|\n)*)/';

    preg_match($regex, file_get_contents(MARKDOWN . "/$filename"), $match);

    if (4 !== count($match)) {
        return false;
    }

    if (null === ($post = json_decode($match[1], true))) {
        return false;
    }

    $post['title'] = trim($match[2]);

    if (!$skip) {
        $post['content'] = $match[3];
    }

    return $post;
}
