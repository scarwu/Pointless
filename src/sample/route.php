<?php
/**
 * Built-in Web Server Route
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

define('APP_ENV', getenv('APP_ENV'));
define('APP_ROOT', getenv('APP_ROOT'));

require APP_ROOT . '/viewer/index.php';

// $root = file_get_contents("{$_SERVER['DOCUMENT_ROOT']}/default");

// // Define Constant
// define('BLOG_ROOT', $root);
// define('BLOG_BUILD', BLOG_ROOT . '/build');

// // Require Config
// require BLOG_ROOT . '/config.php';

// // Set Variables
// $base = BLOG_BUILD;
// $mime = [
//     'html' => 'text/html',
//     'css' => 'text/css',
//     'js' => 'text/javascript',
//     'json' => 'application/json',
//     'xml' => 'application/xml',

//     'jpg' => 'image/jpeg',
//     'png' => 'image/png',
//     'gif' => 'image/gif',

//     'woff' => 'application/font-woff',
//     'ttf' => 'font/opentype'
// ];

// // Built-in Web Server Route
// $pattern = '/^' . str_replace('/', '\/', $config['blog']['baseUrl']) . '/';

// if (!preg_match($pattern, $_SERVER['REQUEST_URI'])) {
//     header("Location:http://{$_SERVER['HTTP_HOST']}{$config['blog']['baseUrl']}");
// }

// $pattern = '/^' . str_replace('/', '\/', $config['blog']['baseUrl']) . '(.+)/';

// if (preg_match($pattern, $_SERVER['REQUEST_URI'], $match)) {
//     $base = "{$base}/" . urldecode($match[1]);
// }

// $base = is_dir($base) ? "{$base}/index.html" : $base;
// $base = explode('?', $base)[0];

// // Set Constent Type
// $extName = explode('.', $base);
// $extName = array_pop($extName);

// if (isset($mime[$extName])) {
//     header("Content-type: {$mime[$extName]}");
// }

// // Response Content
// if (file_exists($base)) {
//     echo file_get_contents($base);

//     return true;
// }

// return false;
