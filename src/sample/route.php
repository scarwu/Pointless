<?php
/**
 * Built-in Web Server Route
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

$root = file_get_contents("{$_SERVER['DOCUMENT_ROOT']}/default");

// Define Constant
define('BLOG_ROOT', $root);
define('BLOG_BUILD', BLOG_ROOT . '/build');

// Require Config
require BLOG_ROOT . '/config.php';

// Set Variables
$base = BLOG_BUILD;
$mime = [
    'html' => 'text/html',
    'css' => 'text/css',
    'js' => 'text/javascript',
    'json' => 'application/json',
    'xml' => 'application/xml',

    'jpg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',

    'woff' => 'application/font-woff',
    'ttf' => 'font/opentype'
];

// Built-in Web Server Route
$pattern = '/^' . str_replace('/', '\/', $config['blog']['base']) . '/';

if (!preg_match($pattern, $_SERVER['REQUEST_URI'])) {
    header("Location:http://{$_SERVER['HTTP_HOST']}{$config['blog']['base']}");
}

$pattern = '/^' . str_replace('/', '\/', $config['blog']['base']) . '(.+)/';

if (preg_match($pattern, $_SERVER['REQUEST_URI'], $match)) {
    $base = "{$base}/" . urldecode($match[1]);
}

$base = is_dir($base) ? "{$base}/index.html" : $base;
$base = explode('?', $base)[0];

// Set Constent Type
$ext_name = explode('.', $base);
$ext_name = array_pop($ext_name);

if (isset($mime[$ext_name])) {
    header("Content-type: {$mime[$ext_name]}");
}

// Response Content
if (file_exists($base)) {
    echo file_get_contents($base);

    return true;
}

return false;
