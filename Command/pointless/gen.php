<?php

class pointless_gen extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		$all = new pointless_gen_all();
		$all->Run();
	}
}
