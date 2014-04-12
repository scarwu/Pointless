#!/usr/bin/env php
<?php
/**
 * Phar Release
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

$root = realpath(dirname(__FILE__) . '/..');

// Rewrite VERSION
if (isset($_SERVER['argv'][1])) {
    file_put_contents("$root/VERSION", $_SERVER['argv'][1]);
}

// Call Builder
system("php $root/script/build.php");

// Clear Phar
if (file_exists("$root/bin/poi")) {
    unlink("$root/bin/poi");
}

// Copy Phar to Bin
copy("$root/poi.phar", "$root/bin/poi");
