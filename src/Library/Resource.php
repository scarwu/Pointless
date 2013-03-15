<?php
/**
 * Resource Pool
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

class Resource {

	/**
	 * @var array
	 */
	private static $_resource = array();

	private function __construct() {}

	/**
	 * Get Resource
	 * 
	 * @param string
	 * @return array
	 */
	public static function get($index) {
		return isset(self::$_resource[$index]) ? self::$_resource[$index] : NULL;
	}

	/**
	 * Set Resource
	 *
	 * @param string
	 * @param array
	 */
	public static function set($index, $data) {
		if(!isset(self::$_resource[$index]))
			self::$_resource[$index] = array();

		self::$_resource[$index][] = $data;
	}
}