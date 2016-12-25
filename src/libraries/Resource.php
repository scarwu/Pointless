<?php
/**
 * Resource Pool
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

class Resource
{
    /**
     * @var array
     */
    private static $resource = [];

    private function __construct() {}

    /**
     * Get Resource
     *
     * @param string
     * @return array
     */
    public static function get($index)
    {
        if (array_key_exists($index, self::$resource)) {
            return self::$resource[$index];
        }

        return null;
    }

    /**
     * Set Resource
     *
     * @param string
     * @param array
     */
    public static function set($index, $data)
    {
        self::$resource[$index] = $data;
    }

    /**
     * Append Resource
     *
     * @param string
     * @param array
     */
    public static function append($index, $data)
    {
        if (!array_key_exists($index, self::$resource)) {
            self::$resource[$index] = [];
        }

        self::$resource[$index][] = $data;
    }
}
