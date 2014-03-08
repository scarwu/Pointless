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

$root = realpath(dirname(__FILE__)) . '/..';
$stub = file_get_contents("$root/script/stub.php");
$version = trim(file_get_contents("$root/VERSION"));

// Clear Phar
if (file_exists("$root/bin/poi'")) {
    unlink("$root/bin/poi");
}

// Create Phar
$phar = new Phar('bin/poi.phar');
$phar->setAlias('poi.phar');
$phar->setStub(sprintf($stub, $version, time()));
$phar->buildFromDirectory("$root/src", '/(\.php|\.md|\.js|\.css)/');
$phar->compressFiles(Phar::GZ);
$phar->stopBuffering();

// Setting Phar is Executable
chmod("$root/bin/poi.phar", 0755);
rename("$root/bin/poi.phar", "$root/bin/poi");
