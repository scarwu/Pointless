<?php
/**
 * Utility
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

class Utility
{
    /**
     * Recursive Copy
     *
     * @param string
     * @param string
     */
    static public function copy($src, $dest)
    {
        if (file_exists($src)) {
            if (is_dir($src)) {
                if (!file_exists($dest)) {
                    mkdir($dest, 0755, true);
                }

                $handle = opendir($src);
                while ($file = readdir($handle)) {
                    if (!in_array($file, ['.', '..', '.git'])) {
                        self::copy("$src/$file", "$dest/$file");
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
    static public function remove($path = null, $self = null)
    {
        if (file_exists($path)) {
            if (is_dir($path)) {
                $handle = opendir($path);
                while ($file = readdir($handle)) {
                    if (!in_array($file, ['.', '..', '.git'])) {
                        self::remove("$path/$file");
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
