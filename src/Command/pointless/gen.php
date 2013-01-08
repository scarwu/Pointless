<?php

class pointless_gen extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		$all = new pointless_gen_all();
		$all->run();
	}
}
