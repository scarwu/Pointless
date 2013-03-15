<?php

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class Config extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		system(sprintf("%s %s < `tty` > `tty`", FILE_EDITOR, USER_DATA . 'Config.php'));
	}
}
