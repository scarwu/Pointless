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
    private static $_resource = [];

    private function __construct() {}

    /**
     * Get Resource
     *
     * @param string $key
     *
     * @return mixed
     */
    public static function get(string $key)
    {
        if (true === array_key_exists($key, self::$_resource)) {
            return self::$_resource[$key];
        }

        return null;
    }

    /**
     * Set Resource
     *
     * @param string $key
     * @param mixed $data
     */
    public static function set(string $key, $data): bool
    {
        self::$_resource[$key] = $data;

        return true;
    }

    /**
     * Append Resource
     *
     * @param string $key
     * @param array $data
     */
    public static function append(string $key, $data): bool
    {
        if (false === array_key_exists($key, self::$_resource)) {
            self::$_resource[$key] = [];
        }

        self::$_resource[$key][] = $data;

        return true;
    }
}
