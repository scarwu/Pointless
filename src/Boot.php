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

if(!file_exists(POINTLESS_HOME . 'status.json')) {
	$handle = fopen(POINTLESS_HOME . 'status.json', 'w+');
	fwrite($handle, json_encode(array(
		'current' => NULL,
		'list' => array()
	)));
	fclose($handle);
}

/**
 * Load Blog Setting
 */
$status = json_decode(file_get_contents(POINTLESS_HOME . 'status.json'), TRUE);
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