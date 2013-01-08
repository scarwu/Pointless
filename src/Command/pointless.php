<?php

class pointless extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		$help = new pointless_help();
		$help->run();
	}
}
