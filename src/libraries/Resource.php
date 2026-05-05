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
    private static array $_resource = [];

    private function __construct() {}

    /**
     * Get Resource
     *
     * @param string $key
     *
     * @return mixed
     */
    public static function get(string $key): mixed
    {
        if (isset(self::$_resource[$key])) {
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
    public static function set(string $key, mixed $data): bool
    {
        self::$_resource[$key] = $data;

        return true;
    }

    /**
     * Append Resource
     *
     * @param string $key
     * @param mixed $data
     */
    public static function append(string $key, mixed $data): bool
    {
        if (false === array_key_exists($key, self::$_resource)) {
            self::$_resource[$key] = [];
        }

        self::$_resource[$key][] = $data;

        return true;
    }
}
