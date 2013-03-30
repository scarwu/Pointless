#!/usr/bin/env php
<?php
/**
 * Phar Builder
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

// Clear Phar
if(file_exists(dirname(__FILE__) . '/bin/poi'))
	unlink(dirname(__FILE__) . '/bin/poi');

// Setting Stub
$stub = <<<EOF
#!/usr/bin/env php
<?php
Phar::mapPhar('poi.phar');
define('BUILD_TIMESTAMP', %d);
define('BIN_LOCATE', realpath(dirname(__FILE__)));
define('ROOT', 'phar://poi.phar/');
require ROOT. 'Boot.php';
__HALT_COMPILER();
?>
EOF;

// Create Phar
$phar = new Phar('bin/poi.phar');
$phar->setAlias('poi.phar');
$phar->setStub(sprintf($stub, time()));
$phar->buildFromDirectory(dirname(__FILE__) . '/src', '/(\.php|\.md|\.js|\.css)/');
$phar->compressFiles(Phar::GZ);
$phar->stopBuffering();

// Setting Phar is Executable
chmod(dirname(__FILE__) . '/bin/poi.phar', 0755);
rename(dirname(__FILE__) . '/bin/poi.phar', dirname(__FILE__) . '/bin/poi');