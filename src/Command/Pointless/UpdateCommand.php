<?php
/**
 * Pointless Update Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
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
		if(!defined('BUILD_TIMESTAMP')) {
			IO::writeln('Development version can not be updated.', 'red');
			return;
		}

		$branch = $this->hasOptions('u') ? 'develop' : 'master';
		$remote = "https://raw.github.com/scarwu/Pointless/$branch/bin/poi";
		$path = defined('BIN_LOCATE') ? BIN_LOCATE : '/usr/local/bin';

		if(!is_dir($path)) {
			IO::writeln("$path is not a directory", 'red');
			return FALSE;
		}

		if(!is_writable($path)) {
			IO::writeln("Permission denied: $path", 'red');
			return FALSE;
		}

		system("wget $remote -O /tmp/poi");
		system('chmod +x /tmp/poi');

		// Reset Timestamp
		$handle = fopen(POINTLESS_HOME . 'Timestamp', 'w+');
		fwrite($handle, '0');
		fclose($handle);

		IO::writeln('Update finish.', 'green');
		if(isset($_SERVER['SUDO_USER'])) {
			$user = $_SERVER['SUDO_USER'];
			system("sudo -u $user /tmp/poi version");
		}
		else {
			system('/tmp/poi version');
		}
		
		system("mv /tmp/poi $path");
	}
}
