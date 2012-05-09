<?php

class pointless_help extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		NanoIO::Writeln("Welcome to use Pointless.", 'yellow');
		NanoIO::Writeln("Available Commads:");
		NanoIO::Writeln("    help            - Show help.");
		NanoIO::Writeln("    init            - Initialize Poinless.");
		NanoIO::Writeln("    gen (all)       - Generate Blog.");
		NanoIO::Writeln("    gen css         - Generate and Compress CSS.");
		NanoIO::Writeln("    gen js          - Generate and Compress JavaScript.");
		NanoIO::Writeln("    gen clean       - Clean Blog Files.");
		NanoIO::Writeln("    article (list)  - List Your Articles.");
		NanoIO::Writeln("    article add     - Create New Articles.");
		NanoIO::Writeln("    blogpage (list) - List Your Blog Pages.");
		NanoIO::Writeln("    blogpage add    - Create New Blog Pages.");
	}
}
