<?php

class pointless_help extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		Text::Write("Welcome to use Pointless.\n", 'yellow');
		Text::Write("Available Commads:\n");
		Text::Write("    help            - Show help.\n");
		Text::Write("    init            - Initialize Poinless.\n");
		Text::Write("    gen (all)       - Generate Blog.\n");
		Text::Write("    gen css         - Generate and Compress CSS.\n");
		Text::Write("    gen js          - Generate and Compress JavaScript.\n");
		Text::Write("    article (list)  - List Your Articles.\n");
		Text::Write("    article add     - Create New Articles.\n");
		Text::Write("    blogpage (list) - List Your Blog Pages.\n");
		Text::Write("    blogpage add    - Create New Blog Pages.\n");
	}
}
