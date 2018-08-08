<?php
/**
 * Resource Pool
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Library;

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
     * @param string $index
     *
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
     * @param string $index
     * @param array $data
     */
    public static function set($index, $data)
    {
        self::$resource[$index] = $data;
    }

    /**
     * Append Resource
     *
     * @param string $index
     * @param array $data
     */
    public static function append($index, $data)
    {
        if (false === array_key_exists($index, self::$resource)) {
            self::$resource[$index] = [];
        }

        self::$resource[$index][] = $data;
    }
}
