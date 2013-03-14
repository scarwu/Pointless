<?php

class pointless_version extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		NanoIO::writeln('Pointless - 0.9.4 Beta');
	}
}
