<?php
/**
 * Pointless Gen Css Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

namespace Pointless\Gen;

use NanoCLI\Command;
use NanoCLI\IO;
use Compress;

class Css extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		require LIBRARY . 'Compress.php';
		
		IO::writeln("Clean CSS ...", 'yellow');
		if(file_exists(PUBLIC_FOLDER . 'main.css'))
			unlink(PUBLIC_FOLDER . 'main.css');

		IO::writeln("Compress CSS ...", 'yellow');
		$Compress = new Compress();
		$Compress->css(THEME_CSS, PUBLIC_FOLDER . 'theme');
	}
}
