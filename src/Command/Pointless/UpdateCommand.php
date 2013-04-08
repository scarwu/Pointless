<?php
/**
 * Pointless Update Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class UpdateCommand extends Command {
	public function __construct() {
		parent::__construct();
	}

	public function help() {
		IO::writeln('    update     - Self-update');
		IO::writeln('    update -u  - Use unstable version');
	}
	
	public function run() {
		$branch = $this->hasOptions('u') ? 'develop' : 'master';
		$remote = "https://raw.github.com/scarwu/Pointless/$branch/bin/poi";
		$path = defined('BIN_LOCATE') ? BIN_LOCATE : '/usr/local/bin';

		if(!is_dir($path)) {
			IO::writeln($path . ' is not a directory', 'red');
			return FALSE;
		}

		if(!is_writable($path)) {
			IO::writeln('Permission denied: ' . $path, 'red');
			return FALSE;
		}

		system('wget ' . $remote . ' -O /tmp/poi');
		system('chmod +x /tmp/poi');

		IO::writeln('Update finish.', 'green');
		system('/tmp/poi version');
		system('mv /tmp/poi ' . $path);
	}
}
