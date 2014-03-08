#!/usr/bin/env php
<?php
/**
 * Phar Builder
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

$root = realpath(dirname(__FILE__) . '/..');

$version = trim(file_get_contents("$root/VERSION"));

$stub = file_get_contents("$root/script/stub.php");
$stub = sprintf($stub, $version, time());

$regex = str_replace('/', '\/', $root);
$regex = "/^$regex\/(vendor|src)/";

// Clear Phar
if (file_exists("$root/bin/poi'")) {
    unlink("$root/bin/poi");
}

// Create Phar
$phar = new Phar("$root/bin/poi.phar");
$phar->setAlias('poi.phar');
$phar->setStub($stub);
$phar->buildFromDirectory($root, $regex);
$phar->compressFiles(Phar::GZ);
$phar->stopBuffering();

// Setting Phar is Executable
chmod("$root/bin/poi.phar", 0755);
rename("$root/bin/poi.phar", "$root/bin/poi");
