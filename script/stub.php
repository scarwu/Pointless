#!/usr/bin/env php
<?php
Phar::mapPhar('poi.phar');
define('BUILD_VERSION', '%s');
define('BUILD_TIMESTAMP', %d);
define('ROOT', 'phar://poi.phar');
require ROOT . '/Boot.php';
__HALT_COMPILER();
?>