<?php
/**
 * Pointless Switch Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class Switch extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function help() {
		
	}

	public function run() {
		// Initialize Blog
		initBlog();
	}
}
