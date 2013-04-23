<?php
/**
 * Bootstrap
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

// Set default timezone
date_default_timezone_set('Etc/UTC');

/**
 * Path Define and Copy Files
 */
define('PLUGIN', ROOT . 'Plugin/');
define('LIBRARY', ROOT . 'Library/');

require LIBRARY . 'GeneralFunction.php';

/**
 * User Data
 */
define('POINTLESS_HOME', $_SERVER['HOME'] . '/.pointless/');

if(!file_exists(POINTLESS_HOME))
	mkdir(POINTLESS_HOME, 0755, TRUE);

if(!file_exists(POINTLESS_HOME . 'Status.json')) {
	$handle = fopen(POINTLESS_HOME . 'Status.json', 'w+');
	fwrite($handle, json_encode(array(
		'current' => NULL,
		'list' => array()
	)));
	fclose($handle);
}

if(defined('BUILD_TIMESTAMP')) {
	if(!file_exists(POINTLESS_HOME . 'Timestamp')) {
		$handle = fopen(POINTLESS_HOME . 'Timestamp', 'w+');
		fwrite($handle, BUILD_TIMESTAMP);
		fclose($handle);
	}

	if(!file_exists(POINTLESS_HOME . 'Sample'))
		recursiveCopy(ROOT . 'Sample', POINTLESS_HOME . 'Sample');

	if(BUILD_TIMESTAMP != file_get_contents(POINTLESS_HOME . 'Timestamp')) {
		recursiveRemove(POINTLESS_HOME . 'Sample');
		recursiveCopy(ROOT . 'Sample', POINTLESS_HOME . 'Sample');

		$handle = fopen(POINTLESS_HOME . 'Timestamp', 'w+');
		fwrite($handle, BUILD_TIMESTAMP);
		fclose($handle);
	}
}

/**
 * Load Blog Setting
 */
$status = json_decode(file_get_contents(POINTLESS_HOME . 'Status.json'), TRUE);
if(!count($status['list']) == 0)
	define('CURRENT_BLOG', $status['list'][$status['current']]);

/**
 * Load NanoCLI and Setting
 */
require PLUGIN . 'NanoCLI/src/NanoCLI/Loader.php';

// Register NanoCLI Autoloader
NanoCLI\Loader::register('NanoCLI', PLUGIN . 'NanoCLI/src');
NanoCLI\Loader::register('Pointless', ROOT . 'Command');

spl_autoload_register('NanoCLI\Loader::load');

$pointless = new Pointless();
$pointless->init();