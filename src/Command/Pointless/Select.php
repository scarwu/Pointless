<?php
/**
 * Pointless Select Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class Select extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function help() {
		IO::writeln('    select <blog name>');
		IO::writeln('               - Select exists blog');
		IO::writeln('    select -l  - List all blogs');
		IO::writeln('    select -d <blog name>');
		IO::writeln('               - Delete blog');
	}

	public function run() {
		$status = json_decode(file_get_contents(POINTLESS_HOME . 'status.json'), TRUE);

		if($this->hasArguments()) {
			$blog_name = $this->getArguments(0);
			if($status['list'][$blog_name]) {
				$status['current'] = $status['list'][$blog_name];

				$handle = fopen(POINTLESS_HOME . 'status.json', 'w+');
				fwrite($handle, json_encode($status));
				fclose($handle);

				IO::writeln('Blog is switch to ' . $blog_name);
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

		if($this->hasOptions('d')) {
			$blog_name = $this->getOptions('d');
			if($status['list'][$blog_name]) {
				$status['current'] = $status['list'][$blog_name];
				IO::writeln('Blog is switch to ' . $blog_name);
			}
			else
				IO::writeln($blog_name . ' is not exists.', 'red');

			return;
		}

		IO::writeln('Please enter blog name.', 'red');
	}
}
