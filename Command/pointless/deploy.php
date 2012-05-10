<?php

class pointless_deploy extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		chdir(BLOG_PUBLIC);
		exec('git add .');
		exec('git add -u');
		exec('git commit -m "' . date("Y-m-d H:i:s", time()) . '"');
		exec('git push');
	}
}
