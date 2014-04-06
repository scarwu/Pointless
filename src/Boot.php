<?php
/**
 * Bootstrap
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

// Composer Autoloader
require VENDOR . '/autoload.php';

// NanoCLI Command Loader
NanoCLI\Loader::set('Pointless', ROOT . '/Command');
NanoCLI\Loader::register();

// Run Pointless Command
$pointless = new Pointless();
$pointless->init();
