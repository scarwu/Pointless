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

// Auto update vendor
if (!file_exists("{$root}/src/vendor")) {
    chdir($root);
    system('composer install');
}

// Copy File to temp
if (file_exists("{$root}/temp")) {
    Pointless\Library\Utility::remove("{$root}/temp");
}

foreach ([
    'commands',
    'formats',
    'extends',
    'libraries',
    'sample',
    'boot.php',
    'constant.php',
    'vendor/autoload.php',
    'vendor/composer/ClassLoader.php',
    'vendor/composer/autoload_classmap.php',
    'vendor/composer/autoload_namespaces.php',
    'vendor/composer/autoload_psr4.php',
    'vendor/composer/autoload_real.php',
    'vendor/composer/autoload_static.php',
    'vendor/scarwu/pack/src',
    'vendor/scarwu/nanocli/src',
    'vendor/erusev/parsedown/Parsedown.php'
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
$phar->setStub(file_get_contents("{$root}/src/stub.php"));
$phar->buildFromDirectory("{$root}/temp");
$phar->compressFiles(Phar::GZ);
$phar->stopBuffering();

// Setting Phar is Executable
chmod("{$root}/poi.phar", 0755);

// Release Phar
if (isset($_SERVER['argv'][1])
    && '-r' === $_SERVER['argv'][1]) {

    // Clear Phar
    if (file_exists("{$root}/bin/poi")) {
        unlink("{$root}/bin/poi");
    }

    // Move Phar to Bin
    rename("{$root}/poi.phar", "{$root}/bin/poi");
}
