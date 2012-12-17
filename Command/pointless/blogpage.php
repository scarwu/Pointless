<?php

class pointless_blogpage extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		$list = new pointless_blogpage_edit();
		$list->run();
	}
}
