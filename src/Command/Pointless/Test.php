<?php
/**
 * Pointless Test Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class Test extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		list($major, $minor, $release) = explode('.', PHP_VERSION);
		$version = $major * 10000 + $minor *100 + $release;

		if($version < 50400) {
			IO::writeln('Built-in server start failed.', 'red');
			return;
		}

		$port = $this->hasConfigs() ? $this->getConfigs('port') : 3000;
		system(sprintf("php -S localhost:%s -t %s < `tty` > `tty`", $port, PUBLIC_FOLDER));
	}
}
