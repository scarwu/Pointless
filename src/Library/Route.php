<?php
/**
 * Built-in Web Server Route
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

print_r($_SERVER);

$status = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/status.json'), TRUE);

require $status['list'][$status['current']] . '/Config.php';

$path = str_replace('/', '\/', BLOG_PATH);
if(!preg_match("/^$path/", $_SERVER['REQUEST_URI']))
	header("Location:http://{$_SERVER['HTTP_HOST']}" . BLOG_PATH);

echo $_SERVER['REQUEST_URI'];