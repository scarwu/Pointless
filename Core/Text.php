<?php

class Text {
	static private $_color = array(
		'red' => '0;31',
		'green' => '0;32',
		'blue' => '0;34',
		'yellow' => '1;33',
	);
	
	public static function Write($msg, $color = NULL) {
		if(NULL !== $color && isset(Text::$_color[$color]))
			$msg = sprintf("\033[%sm%s\033[m", Text::$_color[$color], $msg);

		echo $msg;
	}
}
