<?php
/**
 * Pointless Test Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class TestCommand extends Command {
	public function __construct() {
		parent::__construct();
	}

	public function help() {
		IO::writeln('    test       - Start built-in web server');
		IO::writeln('    --port=<port number>');
		IO::writeln('               - Set port number');
	}
	
	public function run() {
		if(!defined('CURRENT_BLOG')) {
			IO::writeln('Please use "poi init <blog name>" to initialize blog.', 'red');
			return;
		}

		// Initialize Blog
		initBlog();
		
		list($major, $minor, $release) = explode('.', PHP_VERSION);
		$version = $major * 10000 + $minor *100 + $release;

		if($version < 50400) {
			IO::writeln('Built-in server start failed.', 'red');
			return;
		}

		$route_script = (defined('BUILD_TIMESTAMP') ? POINTLESS_HOME . 'Sample/' : LIBRARY) . 'Route.php';
		$port = $this->hasConfigs() ? $this->getConfigs('port') : 3000;

		system(sprintf("php -S localhost:$port -t %s $route_script < `tty` > `tty`", POINTLESS_HOME));
	}
}
