<?php
/**
 * Utility
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Library;

class Utility
{
    private function __construct() {}

    /**
     * Path Replace
     *
     * @param string $filename
     * @param bool $skip
     *
     * @return string
     */
    public static function pathReplace($filename, $skip = false)
    {
        $char = [
            "'", '"', '&', '$', '=',
            '!', '?', '<', '>', '|',
            '(', ')', ':', ';', '@',
            '#', '%', '^', '*', ',',
            '~', '`', '\\'
        ];

        if (false === $skip) {
            $filename = str_replace(['.', '/'], '-', $filename);
        }

        $filename = str_replace($char, '', $filename);

        return stripslashes($filename);
    }

    /**
     * Command Exists
     *
     * @param string $command
     *
     * @return bool
     */
    public static function commandExists($command)
    {
        foreach (explode(':', $_SERVER['PATH']) as $path) {
            if (file_exists("{$path}/{$command}")) {
                return true;
            }
        }

        return false;
    }

    /**
     * Recursive Change Owner and Group
     *
     * @param string $path
     * @param string $user
     * @param string $group
     *
     * @return bool
     */
    public static function chown($path = null, $user = null, $group = null)
    {
        if (false === file_exists($path)) {
            return false;
        }

        chown($path, $user);
        chgrp($path, $group);

        if (is_dir($path)) {
            $handle = opendir($path);

            while ($filename = readdir($handle)) {
                if (in_array($filename, ['.', '..'])) {
                    continue;
                }

                self::chown("{$path}/{$command}", $user, $group);
            }

            closedir($handle);
        }
    }

    /**
     * Recursive mkdir
     *
     * @param string $src
     *
     * @return bool
     */
    public static function mkdir($path)
    {
        if (file_exists($path)) {
            return true;
        }

        return mkdir($path, 0755, true);
    }

    /**
     * Recursive Copy
     *
     * @param string $src
     * @param string $dest
     *
     * @return bool
     */
    public static function copy($src = null, $dest = null)
    {
        if (false === file_exists($src)) {
            return false;
        }

        if (is_dir($src)) {
            if (false === file_exists($dest)) {
                mkdir($dest, 0755, true);
            }

            $handle = opendir($src);

            while ($filename = readdir($handle)) {
                if (in_array($filename, ['.', '..', '.git'])) {
                    continue;
                }

                self::copy("{$src}/{$filename}", "{$dest}/{$filename}");
            }

            closedir($handle);
        } else {
            if (false === file_exists(dirname($dest))) {
                mkdir(dirname($dest), 0755, true);
            }

            copy($src, $dest);
        }
    }

    /**
     * Recursive Remove
     *
     * @param string $path
     * @param string $self
     *
     * @return bool
     */
    public static function remove($path = null, $self = null)
    {
        if (false === file_exists($path)) {
            return false;
        }

        if (is_dir($path)) {
            $handle = opendir($path);

            while ($filename = readdir($handle)) {
                if (in_array($filename, ['.', '..', '.git'])) {
                    continue;
                }

                self::remove("{$path}/{$filename}");
            }

            closedir($handle);

            if ($path !== $self) {
                return rmdir($path);
            }
        } else {
            return unlink($path);
        }
    }
}
