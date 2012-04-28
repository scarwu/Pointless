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
			$command = array_shift(CLI::$_argv);
			CLI::$prefix .= '_' . $command;
			$class = CLI::$prefix;
			try {
				$class = new $class();
				$class->Init();
			}
			catch(Exception $e) {
				Text::Write("Command $command is not found.\n", 'red');
			}
		}
		else
			$this->Run();
	}
	
	public function Run() {}
}
