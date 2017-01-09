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

$stub = file_get_contents("{$root}/scripts/stub.php");

// Auto update vendor
chdir($root);

if (!file_exists("{$root}/src/vendor")) {
    system('composer install');
}

// Copy File to temp
if (file_exists("{$root}/temp")) {
    Pointless\Library\Utility::remove("{$root}/temp");
}

foreach ([
    'commands',
    'doctypes',
    'extends',
    'libraries',
    'sample',
    'boot.php',
    'constant.php',
    'vendor/autoload.php',
    'vendor/composer',
    'vendor/scarwu/pack/src',
    'vendor/scarwu/nanocli/src',
    'vendor/erusev/parsedown'
] as $path) {
    Pointless\Library\Utility::copy("{$root}/src/{$path}", "{$root}/temp/{$path}");
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
