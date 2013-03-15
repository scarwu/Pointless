<?php

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class Deploy extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		chdir(PUBLIC_FOLDER);
		if(file_exists('.git')) {
			exec('git add .');
			exec('git add -u');
			exec(sprintf('git commit -m "%s"', date(DATE_COOKIE)));
			exec('git push');
		}
		else {
			exec('git init');
		}
	}
}
