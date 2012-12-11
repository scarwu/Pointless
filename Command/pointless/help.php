<?php

class pointless_help extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		NanoIO::writeln("Welcome to use Pointless.", 'yellow');
		NanoIO::writeln("Available Commads:");
		NanoIO::writeln("    help            - Show help.");
		NanoIO::writeln("    init            - Initialize Poinless.");
		NanoIO::writeln("    gen (all)       - Generate Blog.");
		NanoIO::writeln("    gen css         - Generate and Compress CSS.");
		NanoIO::writeln("    gen js          - Generate and Compress JavaScript.");
		NanoIO::writeln("    gen clean       - Clean Blog Files.");
		NanoIO::writeln("    article (edit)  - List and Edit Your Articles.");
		NanoIO::writeln("    article add     - Create New Articles.");
		NanoIO::writeln("    blogpage (edit) - List and Edit Your Blog Pages.");
		NanoIO::writeln("    blogpage add    - Create New Blog Pages.");
		NanoIO::writeln("    deploy          - Deploy Blog Using Git.");
	}
}
