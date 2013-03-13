<?php

class pointless_deploy extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		chdir(PUBLIC_FOLDER);
		if(file_exists('.git')) {
			exec('git add .');
			exec('git add -u');
			exec(sprintf('git commit -m "%s - %s"', date("Y-m-d H:i:s", time()), TIMEZONE));
			exec('git push');
		}
		else {
			exec('git init');
		}
	}
}
