<?php

class pointless extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		// Clean static pages
		$clean = new pointless_help();
		$clean->Run();
	}
}
