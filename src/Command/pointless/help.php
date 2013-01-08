<?php

class pointless_help extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		NanoIO::writeln("Welcome to use Pointless.", 'yellow');
		NanoIO::writeln("Available Commads:");
		NanoIO::writeln("    help            - Show help.");
		NanoIO::writeln("    gen (all)       - Generate Blog.");
		NanoIO::writeln("    gen css         - Generate and Compress CSS.");
		NanoIO::writeln("    gen js          - Generate and Compress JavaScript.");
		NanoIO::writeln("    gen clean       - Clean Blog Files.");
		NanoIO::writeln("    add             - Add New Article / Blogpage.");
		NanoIO::writeln("    edit            - List and Edit Article / Blogpage.");
		NanoIO::writeln("    deploy          - Deploy Blog Using Git.");
	}
}
