#!/usr/bin/env php
<?php

// Clear Phar
if(file_exists(dirname(__FILE__) . '/bin/poi.phar'))
	unlink(dirname(__FILE__) . '/bin/poi.phar');

// Setting Stub
$stub = <<<EOF
#!/usr/bin/env php
<?php
define('ROOT', 'phar://poi.phar/');
require ROOT. Boot.php";
__HALT_COMPILER();
?>
EOF;

// Create Phar
$phar = new Phar('bin/poi.phar', 0, 'poi.phar');
$phar->buildFromDirectory(dirname(__FILE__) . '/src');
$phar->setStub($stub);
$phar->compressFiles(Phar::GZ);
$phar->stopBuffering();

// Setting Phar is Executable
chmod(dirname(__FILE__) . '/bin/poi.phar', 0755);