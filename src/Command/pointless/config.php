<?php

class pointless_config extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		system(sprintf("%s %s < `tty` > `tty`", FILE_EDITOR, USER_DATA . 'Config.php'));
	}
}
