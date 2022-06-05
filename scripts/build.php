#!/usr/bin/env php
<?php
/**
 * Phar Builder
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (http://scar.tw)
 * @link        https://github.com/scarwu/Pointless
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
    'tasks',
    'formats',
    'extends',
    'libraries',
    'controllers',
    'handles',
    'extesions',
    'scripts',
    'styles',
    'sample',
    'route.php',
    'boot.php',
    'constant.php',
    'vendor/autoload.php',
    'vendor/composer/ClassLoader.php',
    'vendor/composer/autoload_classmap.php',
    'vendor/composer/autoload_namespaces.php',
    'vendor/composer/autoload_psr4.php',
    'vendor/composer/autoload_real.php',
    'vendor/composer/autoload_static.php',
    'vendor/composer/platform_check.php',
    'vendor/myclabs/deep-copy',
    'vendor/scarwu/oni/src',
    'vendor/erusev/parsedown/Parsedown.php'
] as $path) {
    Pointless\Library\Utility::copy("{$root}/src/{$path}", "{$root}/temp/{$path}");
}

// Clear Phar
if (file_exists("{$root}/poi.phar")) {
    unlink("{$root}/poi.phar");
}

// Create Stub
$stub = sprintf(<<<EOF
#!/usr/bin/env php
<?php
Phar::mapPhar('poi.phar');
define('BUILD_TIMESTAMP', '%s');
define('APP_ENV', 'production');
define('APP_ROOT', 'phar://poi.phar');
define('BIN_LOCATE', realpath(__FILE__));
require APP_ROOT . '/boot.php';
__HALT_COMPILER();
?>
EOF, time());

// Create Phar
$phar = new Phar("{$root}/poi.phar");
$phar->setAlias('poi.phar');
$phar->setStub($stub);
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

echo "Phar is build to {$root}/poi.phar, if you want to release then using \"-r\".\n";