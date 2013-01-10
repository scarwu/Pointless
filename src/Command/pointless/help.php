<?php

class pointless_help extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		$pointless = <<<EOF
                                           __
      ______  ______  __  ______  ______  / /\______  _____  _____
     / __  /\/ __  /\/ /\/ __  /\/_  __/\/ / /  ___/\/  __/\/  __/\
    / /_/ / / /_/ / / / / /\/ / /\/ /\_\/ / /  ___/\/\  \_\/\  \_\/
   / ____/ /_____/ /_/ /_/ /_/ / /_/ / /_/ /_____/\/____/\/____/\
  /_/\___\/\_____\/\_\/\_\/\_\/  \_\/  \_\/\_____\/\____\/\____\/
  \_\/                                                     0.9.2

EOF;

		NanoIO::writeln($pointless, 'green');
		NanoIO::writeln('    gen        - Generate Blog');
		NanoIO::writeln('    gen css    - Generate and Compress CSS');
		NanoIO::writeln('    gen js     - Generate and Compress JavaScript');
		NanoIO::writeln('    add        - Add New Article or Blog Page');
		NanoIO::writeln('    edit       - List and Edit Article or Blog Page');
		NanoIO::writeln('    delete     - Delete Article or Blog Page');
		NanoIO::writeln('    confng     - Modify Blog Config');
		NanoIO::writeln('    deploy     - Deploy Blog');
		NanoIO::writeln('    update     - Self-update');
	}
}
