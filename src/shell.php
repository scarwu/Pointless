#!/usr/bin/env php
<?php
/**
 * Pointless PHP Shell
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

// Define Variables
define('APP_ENV', 'development');
define('APP_ROOT', realpath(dirname(__FILE__)));

// Load Bootstrap
require APP_ROOT . '/boot/cli.php';
