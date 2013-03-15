<?php

use NanoCLI\Command;

class Pointless extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		$help = new Pointless\Help();
		$help->run();
	}
}
