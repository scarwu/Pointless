#!/usr/bin/env php
<?php
Phar::mapPhar('poi.phar');
define('BUILD_VERSION', '%s');
define('BUILD_TIMESTAMP', %d);
define('BIN_LOCATE', realpath(dirname(__FILE__)));
define('ROOT', 'phar://poi.phar');
require ROOT . '/boot.php';
__HALT_COMPILER();
?>
