<?php

class pointless extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		$clean = new pointless_help();
		$clean->Run();
	}
}
