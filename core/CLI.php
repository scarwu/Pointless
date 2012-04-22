<?php

abstract class CLI {
	static public $argv;
	static public $prefix;
	
	public function __construct() {
		if(!is_array(CLI::$argv))
			CLI::$argv = array_slice($_SERVER['argv'], 1);
	}
	
	public function init() {
		if(count(CLI::$argv) > 0) {
			CLI::$prefix .= '_' . array_shift(CLI::$argv);
			$class = CLI::$prefix;
			$class = new $class();
			$class->init();
		}
		else
			$this->run();
	}
	
	public function run() {}
}
