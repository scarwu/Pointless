<?php

class pointless extends CLI {
	public function __construct() {
		parent::__construct();
		CLI::$prefix = 'pointless';
	}
	
	public function Run() {
		// Clean static pages
		$clean = new pointless_help();
		$clean->Run();
	}
}
