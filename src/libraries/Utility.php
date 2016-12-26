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
     * @param string
     * @param boolean
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
     * @param string
     * @return boolean
     */
    public static function commandExists($command)
    {
        foreach (explode(':', $_SERVER['PATH']) as $path) {
            if (file_exists("$path/$command")) {
                return true;
            }
        }

        return false;
    }

    /**
     * Recursive Change Owner and Group
     *
     * @param string
     * @param string
     * @param string
     */
    public static function chown($path, $user, $group)
    {
        if (file_exists($path)) {
            chown($path, $user);
            chgrp($path, $group);

            if (is_dir($path)) {
                $handle = opendir($path);
                while ($filename = readdir($handle)) {
                    if (!in_array($filename, ['.', '..'])) {
                        self::chown("$path/$filename", $user, $group);
                    }
                }
                closedir($handle);
            }
        }
    }

    /**
     * Recursive Copy
     *
     * @param string
     * @param string
     */
    public static function copy($src, $dest)
    {
        if (file_exists($src)) {
            if (is_dir($src)) {
                if (!file_exists($dest)) {
                    mkdir($dest, 0755, true);
                }

                $handle = opendir($src);
                while ($filename = readdir($handle)) {
                    if (!in_array($filename, ['.', '..', '.git'])) {
                        self::copy("$src/$filename", "$dest/$filename");
                    }
                }
                closedir($handle);
            } else {
                if (!file_exists(dirname($dest))) {
                    mkdir(dirname($dest), 0755, true);
                }

                copy($src, $dest);
            }
        }
    }

    /**
     * Recursive Remove
     *
     * @param string
     * @param string
     * @return boolean
     */
    public static function remove($path = null, $self = null)
    {
        if (file_exists($path)) {
            if (is_dir($path)) {
                $handle = opendir($path);
                while ($filename = readdir($handle)) {
                    if (!in_array($filename, ['.', '..', '.git'])) {
                        self::remove("$path/$filename");
                    }
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
}
