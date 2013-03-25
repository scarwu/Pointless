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
		IO::writeln('    gen        - Generate blog');
		IO::writeln('    add        - Add new article / blog-page');
		IO::writeln('    edit       - List and edit article / blog-page');
		IO::writeln('    delete     - Delete article / blog-page');
		IO::writeln('    test       - Start built-in web server');
		IO::writeln('    config     - Modify config');
		IO::writeln('    deploy     - Deploy blog to Github');
		IO::writeln('    update     - Self-update');
		IO::writeln('    version    - Show version');
	}
}
