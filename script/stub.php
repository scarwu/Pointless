#!/usr/bin/env php
<?php
Phar::mapPhar('poi.phar');
define('BUILD_VERSION', '%s');
define('BUILD_TIMESTAMP', %d);
define('BIN_LOCATE', realpath(dirname(__FILE__)));
define('ROOT', 'phar://poi.phar/src');
define('VENDOR', 'phar://poi.phar/vendor');
require ROOT . '/Boot.php';
__HALT_COMPILER();
?>
