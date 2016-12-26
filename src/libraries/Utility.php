<?php
/**
 * Utility
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2016, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

class Utility
{
    private function __construct() {}

    /**
     * Path Replace
     *
     * @param string filename
     * @param boolean skip
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

        if (!$skip) {
            $filename = str_replace(['.', '/'], '-', $filename);
        }

        $filename = str_replace($char, '', $filename);

        return stripslashes($filename);
    }

    /**
     * Command Exists
     *
     * @param string command
     *
     * @return boolean
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
     * @param string path
     * @param string user
     * @param string group
     *
     * @return boolean
     */
    public static function chown($path = null, $user = null, $group = null)
    {
        if (!file_exists($path)) {
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
     * Recursive Copy
     *
     * @param string src
     * @param string dest
     *
     * @return boolean
     */
    public static function copy($src = null, $dest = null)
    {
        if (!file_exists($src)) {
            return false;
        }

        if (is_dir($src)) {
            if (!file_exists($dest)) {
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
            if (!file_exists(dirname($dest))) {
                mkdir(dirname($dest), 0755, true);
            }

            copy($src, $dest);
        }
    }

    /**
     * Recursive Remove
     *
     * @param string path
     * @param string self
     *
     * @return boolean
     */
    public static function remove($path = null, $self = null)
    {
        if (!file_exists($path)) {
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
