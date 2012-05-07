<?php

class pointless_article extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		$list = new pointless_article_list();
		$list->Run();
	}
}
