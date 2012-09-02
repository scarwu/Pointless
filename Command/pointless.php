<?php

class pointless extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		$help = new pointless_help();
		$help->Run();
	}
}
