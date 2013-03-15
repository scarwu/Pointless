<?php

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class Help extends Command {
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

		IO::writeln($pointless, 'green');
		IO::writeln('    gen        - Generate Blog');
		IO::writeln('    gen css    - Generate and Compress CSS');
		IO::writeln('    gen js     - Generate and Compress JavaScript');
		IO::writeln('    add        - Add New Article or Blog Page');
		IO::writeln('    edit       - List and Edit Article or Blog Page');
		IO::writeln('    delete     - Delete Article or Blog Page');
		IO::writeln('    config     - Modify Blog Config');
		IO::writeln('    deploy     - Deploy Blog');
		IO::writeln('    update     - Self-update');
		IO::writeln('    version    - Display Version');
	}
}
