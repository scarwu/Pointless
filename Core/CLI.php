<?php

abstract class CLI {
	static public $prefix;
	static private $_argv;
	static private $_color = array(
		'red' => '0;31',
		'green' => '0;32',
		'blue' => '0;34',
		'yellow' => '1;33',
	);
	
	public function __construct() {
		if(!is_array(CLI::$_argv))
			CLI::$_argv = array_slice($_SERVER['argv'], 1);
	}
	
	public function Init() {
		if(count(CLI::$_argv) > 0) {
			CLI::$prefix .= '_' . array_shift(CLI::$_argv);
			$class = CLI::$prefix;
			$class = new $class();
			$class->Init();
		}
		else
			$this->Run();
	}
	
	public function Run() {}
	
	public function Text($msg, $color = NULL) {
		if(NULL !== $color && isset(CLI::$_color[$color]))
			$msg = sprintf("\033[%sm%s\033[m", CLI::$_color[$color], $msg);

		echo $msg;
	}
}
