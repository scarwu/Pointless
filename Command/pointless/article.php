<?php

class pointless_article extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		$list = new pointless_article_edit();
		$list->run();
	}
}
