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
    public static function pathReplace(string $filename, bool $skip = false): string
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
    public static function commandExists(string $command): bool
    {
        foreach (explode(':', $_SERVER['PATH']) as $path) {
            if (true === file_exists("{$path}/{$command}")) {
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
    public static function chown(string $path, string $user, string $group): bool
    {
        if (false === file_exists($path)) {
            return false;
        }

        chown($path, $user);
        chgrp($path, $group);

        if (true === is_dir($path)) {
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
     * @param string $path
     *
     * @return bool
     */
    public static function mkdir(string $path): bool
    {
        if (true === file_exists($path)) {
            return is_dir($path);
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
    public static function copy(string $src, string $dest): bool
    {
        if (false === file_exists($src)) {
            return false;
        }

        if (true === is_dir($src)) {
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

            return true;
        } elseif (true === is_file($src)) {
            if (false === file_exists(dirname($dest))) {
                mkdir(dirname($dest), 0755, true);
            }

            copy($src, $dest);

            return true;
        }

        return true;
    }

    /**
     * Recursive Remove
     *
     * @param string $path
     * @param string $self
     * @param array $skipFilenameList
     *
     * @return bool
     */
    public static function remove(string $path, bool $isKeepRoot = false, array $skipFilenameList = []): bool
    {
        if (false === file_exists($path)) {
            return false;
        }

        $rootPath = $isKeepRoot ? $path : null;

        if (true === is_dir($path)) {
            $handle = opendir($path);

            while ($filename = readdir($handle)) {
                if (in_array($filename, ['.', '..'])
                    || in_array($filename, $skipFilenameList)) {

                    continue;
                }

                self::remove("{$path}/{$filename}");
            }

            closedir($handle);

            if ($path !== $rootPath) {
                rmdir($path);

                return true;
            }
        } elseif (true === is_file($path)){
            unlink($path);

            return true;
        }

        return true;
    }

    /**
     * Is Command Running
     *
     * @param string $command
     *
     * @return bool
     */
    public static function isCommandRunning(string $command): bool
    {
        exec('ps aux', $output);

        $output = array_filter($output, function ($text) use ($command) {
            return strpos($text, $command);
        });

        return 0 < count($output);
    }

    /**
     * Fix Permission
     *
     * @param string $path
     *
     * @return bool
     */
    public static function fixPermission(string $path): bool
    {
        // Check SERVER Variable
        if (false === isset($_SERVER['SUDO_USER'])) {
            return false;
        }

        // Change Owner (Fix Permission)
        self::chown($path, fileowner($_SERVER['HOME']), filegroup($_SERVER['HOME']));

        return true;
    }

    /**
     * Save JSON File
     *
     * @param string $path
     * @param mixed $path
     *
     * @return bool
     */
    public static function saveJsonFile(string $path, $data): bool
    {
        if (true === file_exists($path)) {
            return false;
        }

        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return true;
    }

    /**
     * Load JSON File
     *
     * @param string $path
     *
     * @return mixed
     */
    public static function loadJsonFile(string $path)
    {
        if (false === file_exists($path)) {
            return null;
        }

        return json_decode(file_get_contents($path), true);
    }
}
