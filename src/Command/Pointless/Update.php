<?php
/**
 * Pointless Update Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class Update extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		system('sudo wget https://raw.github.com/scarwu/Pointless/master/bin/poi -O /usr/bin/poi');
		system('sudo chmod +x /usr/bin/poi');
	}
}
