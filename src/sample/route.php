<?php
/**
 * Built-in Web Server Route
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2016, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

$blog = file_get_contents("{$_SERVER['DOCUMENT_ROOT']}/default");

define('BLOG', $blog);

require BLOG . '/config.php';

$path = BLOG . '/temp/';

$pattern = '/^' . str_replace('/', '\/', $config['blog']['base']) . '/';

if (!preg_match($pattern, $_SERVER['REQUEST_URI'])) {
    header("Location:http://{$_SERVER['HTTP_HOST']}{$config['blog']['base']}");
}

$pattern = '/^' . str_replace('/', '\/', $config['blog']['base']) . '(.+)/';

if (preg_match($pattern, $_SERVER['REQUEST_URI'], $match)) {
    $path = $path . urldecode($match[1]);
}

$path = is_dir($path) ? "{$path}/index.html" : $path;
$path = explode('?', $path)[0];

$mime = [
    'html' => 'text/html',
    'css' => 'text/css',
    'js' => 'text/javascript',

    'json' => 'application/json',
    'xml' => 'application/xml',
    'woff' => 'application/font-woff',

    'jpg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',

    'ttf' => 'font/opentype'
];

$ext_name = explode('.', $path);
$ext_name = array_pop($ext_name);

if (isset($mime[$ext_name])) {
    header("Content-type: {$mime[$ext_name]}");
}

if (file_exists($path)) {
    echo file_get_contents($path);

    return true;
}

return false;
