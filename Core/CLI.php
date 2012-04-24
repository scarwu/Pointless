<?php

abstract class CLI {
	static public $_argv;
	static public $_prefix;
	
	public function __construct() {
		if(!is_array(CLI::$_argv))
			CLI::$_argv = array_slice($_SERVER['argv'], 1);
	}
	
	public function Init() {
		if(count(CLI::$_argv) > 0) {
			CLI::$_prefix .= '_' . array_shift(CLI::$_argv);
			$class = CLI::$_prefix;
			$class = new $class();
			$class->Init();
		}
		else
			$this->Run();
	}
	
	public function Run() {}
	
	public function Text($msg, $text_color = NULL, $bg_color = NULL) {
		if(NULL !== $text_color)
			$msg = sprintf($format);
		
		if(NULL !== $bg_color)
			$msg = sprintf($format);
		
		echo $msg;
	}
}
