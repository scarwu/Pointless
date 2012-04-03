<?php

class pointless extends CLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		// Clean static pages
		$clean = new Pointless_help();
		$clean->run();
	}
}
