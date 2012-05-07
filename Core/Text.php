<?php
/**
 * Text
 * 
 * @package		NanoCLI
 * @author		ScarWu
 * @copyright	Copyright (c) 2012, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/NanoCLI
 */

class Text {
	
	/**
	 * @var array
	 */
	static private $_color = array(
		'red' => '0;31',
		'green' => '0;32',
		'blue' => '0;34',
		'yellow' => '1;33',
	);
	
	/**
	 * Write data to STDOUT
	 * 
	 * @param string
	 * @param string
	 */
	public static function Write($msg, $color = NULL) {
		if(NULL !== $color && isset(Text::$_color[$color]))
			$msg = sprintf("\033[%sm%s\033[m", Text::$_color[$color], $msg);
		
		fwrite(STDOUT, $msg);
	}
	
	/**
	 * Read STDIN
	 */
	public static function Read() {
		return trim(fgets(STDIN));
	}
}
