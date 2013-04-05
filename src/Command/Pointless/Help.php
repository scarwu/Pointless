<?php
/**
 * Pointless Help Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

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
		if(!$this->hasArguments()) {
			IO::writeln('    gen        - Generate blog');
			IO::writeln('    add        - Add new article');
			IO::writeln('    edit       - Edit article');
			IO::writeln('    delete     - Delete article');
			IO::writeln('    test       - Start built-in web server');
			IO::writeln('    config     - Modify config');
			IO::writeln('    deploy     - Deploy blog to Github');
			IO::writeln('    update     - Self-update');
			IO::writeln('    version    - Show version');
		}
		else {
			$command = $this->getArguments(0);
			switch($command) {
				case 'gen':
					IO::writeln('    gen        - Generate blog');
					IO::writeln('    gen -css   - Compress CSS');
					IO::writeln('    gen -js    - Compress Javascript');
					break;
				case 'add':
					IO::writeln('    add        - Add new article');
					IO::writeln('    add -s     - Add new Static Page');
					break;
				case 'edit':
					IO::writeln('    edit       - Edit article');
					IO::writeln('    edit -s    - Edit Static Page');
					break;
				case 'delete':
					IO::writeln('    delete     - Delete article');
					IO::writeln('    delete -s  - Delete Static Page');
					break;
				case 'update':
					IO::writeln('    update     - Self-update');
					IO::writeln('    update -u  - Use unstable version');
					break;
				case 'test':
					IO::writeln('    test       - Start built-in web server');
					IO::writeln('    --port=?   - Set port number');
					break;
				default:
					IO::writeln('    No description for ' . $command . '.', 'red');
			}
		}
	}
}
