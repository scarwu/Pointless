<?php

class pointless_deploy extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		$regex = '/^#	deleted:    (.*)/';
		chdir(BLOG_PUBLIC);
		exec('git add .');
		exec('git status', $result);
		foreach($result as $key => $value) {
			if(preg_match($regex, $value, $match))
				exec('git rm ' . $match[1]);
		}
		exec('git commit -m "' . date("Y-m-d H:i:s", time()) . '"');
		exec('git push');
	}
}
