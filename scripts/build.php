#!/usr/bin/env php
<?php
/**
 * Phar Builder
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

$root = realpath(dirname(__FILE__) . '/..');

include "{$root}/src/libraries/Utility.php";

$stub = file_get_contents("{$root}/script/stub.php");
$stub = sprintf($stub, $version, time());

// Auto update vendor
chdir($root);

if (file_exists("{$root}/src/vendor")) {
    system('composer update');
} else {
    system('composer install');
}

// Copy File to temp
if (file_exists("{$root}/temp")) {
    Utility::remove("{$root}/temp");
}

foreach ([
    'src',
    'src/vendor/autoload.php',
    'src/vendor/composer',
    'src/vendor/scarwu/pack/src',
    'src/vendor/scarwu/nanocli/src',
    'src/vendor/erusev/parsedown'
] as $path) {
    Utility::copy("{$root}/{$path}", "{$root}/temp/{$path}");
}

// Clear Phar
if (file_exists("{$root}/poi.phar")) {
    unlink("{$root}/poi.phar");
}

// Create Phar
$phar = new Phar("{$root}/poi.phar");
$phar->setAlias('poi.phar');
$phar->setStub($stub);
$phar->buildFromDirectory("{$root}/temp");
$phar->compressFiles(Phar::GZ);
$phar->stopBuffering();

// Setting Phar is Executable
chmod("{$root}/poi.phar", 0755);
