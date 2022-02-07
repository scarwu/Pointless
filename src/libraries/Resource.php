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
     * @param string $key
     *
     * @return array
     */
    public static function get($key)
    {
        if (false === is_string($key)) {
            return null;
        }

        if (array_key_exists($key, self::$resource)) {
            return self::$resource[$key];
        }

        return null;
    }

    /**
     * Set Resource
     *
     * @param string $key
     * @param array $data
     */
    public static function set($key, $data)
    {
        if (false === is_string($key)
            || false === is_array($data)
        ) {
            return false;
        }

        self::$resource[$key] = $data;

        return true;
    }

    /**
     * Append Resource
     *
     * @param string $key
     * @param array $data
     */
    public static function append($key, $data)
    {
        if (false === is_string($key)
            || false === is_array($data)
        ) {
            return false;
        }

        if (false === array_key_exists($key, self::$resource)) {
            self::$resource[$key] = [];
        }

        self::$resource[$key][] = $data;

        return true;
    }
}
