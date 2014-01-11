<?php
/**
 * Pointless Switch Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class SwitchCommand extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function help() {
		IO::writeln('    switch <blog name>');
		IO::writeln('               - Switch exists blog');
		IO::writeln('    switch -l  - List all blogs');
		IO::writeln('    switch -r <blog name>');
		IO::writeln('               - Remove blog from list');
	}

	public function run() {
		$status = json_decode(file_get_contents(POINTLESS_HOME . 'Status.json'), TRUE);

		if($this->hasArguments()) {
			$blog_name = $this->getArguments(0);
			if(isset($status['list'][$blog_name])) {
				$status['current'] = $blog_name;

				$handle = fopen(POINTLESS_HOME . 'Status.json', 'w+');
				fwrite($handle, json_encode($status));
				fclose($handle);

				IO::writeln('Blog is switch to ' . $blog_name);
			}
			else
				IO::writeln($blog_name . ' is not exists.', 'red');

			return;
		}

		// if($this->hasOptions('i')) {
		// 	$blog_path = $this->getOptions('i');
			
		// 	if($blog_path == '') {
		// 		IO::writeln('Please enter blog path.', 'red');
		// 		return;
		// 	}

		// 	if(!file_exists($blog_path)) {
		// 		IO::writeln($blog_path . ' is not exists.', 'red');
		// 		return;
		// 	}

		// 	$blog_name = explode('/', $blog_path);
		// 	$blog_name = array_pop($blog_name);

		// 	$status = json_decode(file_get_contents(POINTLESS_HOME . 'Status.json'), TRUE);
		// 	$status['current'] = $blog_name;
		// 	$status['list'][$blog_name] = realpath($blog_path);

		// 	$handle = fopen(POINTLESS_HOME . 'Status.json', 'w+');
		// 	fwrite($handle, json_encode($status));
		// 	fclose($handle);

		// 	IO::writeln($blog_name . ' import Completed.', 'green');

		// 	return;
		// }

		if($this->hasOptions('r')) {
			$blog_name = $this->getOptions('r');
			
			if($blog_name == '') {
				IO::writeln('Please enter blog name.', 'red');
				return;
			}

			if(isset($status['list'][$blog_name])) {
				unset($status['list'][$blog_name]);

				if($blog_name == $status['current']) {
					$status['current'] = NULL;

					foreach($status['list'] as $name => $path) {
						$status['current'] = $name;
						break;
					}
				}
				
				$handle = fopen(POINTLESS_HOME . 'Status.json', 'w+');
				fwrite($handle, json_encode($status));
				fclose($handle);

				IO::writeln($blog_name . ' is removed.', 'green');
			}
			else
				IO::writeln($blog_name . ' is not exists.', 'red');

			return;
		}

		if($this->hasOptions('l')) {
			IO::writeln('Current blog: ' . $status['current'] . "\n");
			IO::writeln('Blog list:');
			foreach($status['list'] as $name => $path) {
				IO::writeln($name . ' (' . $path . ')');
			}

			return;
		}

		IO::writeln('Please enter blog name.', 'red');
	}
}
