<?php

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class Version extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		IO::writeln('Pointless - 0.9.4 Beta');
	}
}
