<?php
/**
 * Pointless Version Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class Version extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		$version = 'v0.9.9 Beta';

		if(defined('BUILD_DATE'))
			$version .= ' (' . BUILD_DATE . ')';

		IO::writeln($version);
	}
}
