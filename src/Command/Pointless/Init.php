<?php
/**
 * Pointless Initialize Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class Init extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function help() {
		IO::writeln('    init <blog name>');
		IO::writeln('               - Initialize blog');
	}

	public function run() {
		if(!$this->hasArguments()) {
			IO::writeln('Please enter blog name.', 'red');
			return;
		}

		$blog_name = $this->getArguments(0);
		if(file_exists($_SERVER['PWD'] . '/' . $blog_name)) {
			IO::writeln($blog_name . ' is exists.', 'red');
			return;
		}

		$status = json_decode(file_get_contents(POINTLESS_HOME . 'status.json'), TRUE);
		$status['current'] = $blog_name;
		$status['list'][$blog_name] = $_SERVER['PWD'] . '/' . $blog_name;

		$handle = fopen(POINTLESS_HOME . 'status.json', 'w+');
		fwrite($handle, json_encode($status));
		fclose($handle);

		// Initialize Blog
		initBlog($status['list'][$blog_name]);

		IO::writeln($blog_name . ' is Initialized.', 'green');
	}
}
