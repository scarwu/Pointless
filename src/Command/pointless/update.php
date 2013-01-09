<?php

class pointless_update extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		system('sudo wget https://raw.github.com/scarwu/Pointless/master/bin/poi -O /usr/bin/poi');
		system('sudo chmod +x /usr/bin/poi');
	}
}
