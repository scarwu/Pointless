#!/usr/bin/env php
<?php
/**
 * Pointless PHP Shell
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

$root = realpath(dirname(__FILE__));

// Define Variables
define('ENV', 'development');
define('ROOT', "{$root}/src");

// Load Bootstrap
require ROOT . '/boot.php';
