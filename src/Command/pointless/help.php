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
  \_\/

EOF;

		NanoIO::writeln($pointless, 'green');
		NanoIO::writeln('    gen        - Generate Blog');
		NanoIO::writeln('    gen css    - Generate and Compress CSS');
		NanoIO::writeln('    gen js     - Generate and Compress JavaScript');
		NanoIO::writeln('    add        - Add New Article / Blogpage');
		NanoIO::writeln('    edit       - List and Edit Article / Blogpage');
		NanoIO::writeln('    delete     - Delete Article / Blogpage');
		NanoIO::writeln('    confing    - Modify Blog Config');
		NanoIO::writeln('    deploy     - Deploy Blog Using Git');
		NanoIO::writeln('    update     - Pointless Self-update');
	}
}
