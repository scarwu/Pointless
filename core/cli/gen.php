<?php

class pointless_gen extends CLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		$all = new Pointless_gen_all();
		$all->run();
	}
}
