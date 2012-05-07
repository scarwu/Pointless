<?php

class pointless_article_create extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		Text::Write($msg);
	}
}
