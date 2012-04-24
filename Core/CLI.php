<?php

abstract class CLI {
	static public $prefix;
	static private $_argv;
	
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
}
