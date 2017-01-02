#!/usr/bin/env php
<?php
/**
 * Pointless PHP Shell
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

// Define Variables
define('APP_ENV', 'development');
define('APP_ROOT', realpath(dirname(__FILE__)));
define('HOME_ROOT', $_SERVER['HOME'] . '/.pointless3');

define('IS_SUPER_USER', isset($_SERVER['SUDO_USER']));

// Load Bootstrap
require APP_ROOT . '/boot.php';
