<?php
/**
 * Pointless Config Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class Config extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		if(!defined('CURRENT_BLOG')) {
			IO::writeln('Please use "poi init <blog_name>" to initialize blog.', 'red');
			return;
		}
		
		// Initialize Blog
		initBlog();
		
		system(sprintf("%s %s < `tty` > `tty`", FILE_EDITOR, USER_DATA . 'Config.php'));
	}
}
