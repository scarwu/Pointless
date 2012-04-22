<?php

class pointless extends CLI {
	public function __construct() {
		parent::__construct();
		CLI::$prefix = 'pointless';
	}
	
	public function run() {
		// Clean static pages
		$clean = new Pointless_help();
		$clean->run();
	}
}
