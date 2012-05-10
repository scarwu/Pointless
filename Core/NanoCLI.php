<?php
/**
 * NanoCLI
 * 
 * @package		NanoCLI
 * @author		ScarWu
 * @copyright	Copyright (c) 2012, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/NanoCLI
 */

abstract class NanoCLI {
	
	/**
	 * @var string
	 */
	static public $_argv;
	
	/**
	 * @var string
	 */
	static public $_prefix;
	
	public function __construct() {
		if(!is_array(self::$_argv)) {
			self::$_argv = array_slice($_SERVER['argv'], 1);
			self::$_prefix = NANOCLI_PREFIX;
		}
	}
	
	/**
	 * Init
	 */
	final public function Init() {
		if(count(self::$_argv) > 0) {
			$command = array_shift(self::$_argv);
			self::$_prefix .= '_' . $command;
			$class = self::$_prefix;
			try {
				$class = new $class();
				$class->Init();
			}
			catch(Exception $e) {
				NanoIO::Write("Command \"$command\" is not found.\n", 'red');
			}
		}
		else
			$this->Run();
	}
	
	/**
	 * Execute default function
	 */
	public function Run() {}
}
