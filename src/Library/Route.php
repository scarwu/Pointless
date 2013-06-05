<?php
/**
 * Built-in Web Server Route
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

$status = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/Status.json'), TRUE);

require $status['list'][$status['current']] . '/Config.php';
$path = $status['list'][$status['current']] . '/Public/';

$pattern = '/^' . str_replace('/', '\/', BLOG_PATH) . '/';
if(!preg_match($pattern, $_SERVER['REQUEST_URI']))
	header("Location:http://{$_SERVER['HTTP_HOST']}" . BLOG_PATH);

$pattern = '/^' . str_replace('/', '\/', BLOG_PATH) . '(.+)/';
if(preg_match($pattern, $_SERVER['REQUEST_URI'], $match))
	$path .= urldecode($match[1]);

$path .= is_dir($path) ? '/index.html' : '';

if(preg_match('/\.css$/', $path))
	header('Content-type: text/css');

if(preg_match('/\.js$/', $path))
	header('Content-type: text/javascript');

if(file_exists($path)) {
	echo file_get_contents($path);
	return TRUE;
}

return FALSE;