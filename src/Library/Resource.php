<?php

class Resource {

	/**
	 * 
	 */
	private static $_resource = array();

	/**
	 * 
	 */
	private function __construct() {}

	/**
	 * 
	 */
	public static function get($index) {
		return isset(self::$_resource[$index]) ? self::$_resource[$index] : NULL;
	}

	/**
	 * 
	 */
	public static function set($index, $data) {
		if(!isset(self::$_resource[$index]))
			self::$_resource[$index] = array();

		self::$_resource[$index][] = $data;
	}
}