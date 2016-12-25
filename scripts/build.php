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

include "{$root}/src/libraries/Utility.php";

$stub = file_get_contents("{$root}/script/stub.php");
$stub = sprintf($stub, $version, time());

// Auto update vendor
chdir($root);

if (file_exists("{$root}/vendor")) {
    system('composer update');
} else {
    system('composer install');
}

// Copy File to tmp
if (file_exists("{$root}/tmp")) {
    Utility::remove("{$root}/tmp");
}

foreach ([
    'src',
    'vendor/autoload.php',
    'vendor/composer',
    'vendor/scarwu/pack/src',
    'vendor/scarwu/nanocli/src',
    'vendor/erusev/parsedown'
] as $path) {
    Utility::copy("{$root}/$path", "{$root}/tmp/$path");
}

// Clear Phar
if (file_exists("{$root}/poi.phar")) {
    unlink("{$root}/poi.phar");
}

// Create Phar
$phar = new Phar("{$root}/poi.phar");
$phar->setAlias('poi.phar');
$phar->setStub($stub);
$phar->buildFromDirectory("{$root}/tmp");
$phar->compressFiles(Phar::GZ);
$phar->stopBuffering();

// Setting Phar is Executable
chmod("{$root}/poi.phar", 0755);
