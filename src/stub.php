#!/usr/bin/env php
<?php
Phar::mapPhar('poi.phar');
define('APP_ENV', 'production');
define('APP_ROOT', 'phar://poi.phar');
define('BIN_LOCATE', realpath(dirname(__FILE__)));
require APP_ROOT . '/boot/cli.php';
__HALT_COMPILER();
?>
