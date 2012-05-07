<?php

class pointless_help extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		Text::Write("Welcome to use Pointless.\n", 'yellow');
		Text::Write("Available Commads:\n");
		Text::Write("    help      - List manual.\n");
		Text::Write("    init      - Initialize Poinless.\n");
		Text::Write("    gen       - Generate Blog.\n");
		Text::Write("    article   - Show Your Articles.\n");
		Text::Write("    blogpage  - Show Your Blog Pages.\n");
	}
}
