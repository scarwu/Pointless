<?php
/**
 * Miscellaneous
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Library;

use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Parsedown;
use Oni\CLI\IO;

class Misc {

    /**
     * Show Banner
     */
    public static function showBanner()
    {
        $banner = <<<EOF
                                           __                          _______
      ______  ______  __  ______  ______  / /\______  _____  _____    / _____/\
     / __  /\/ __  /\/ /\/ __  /\/_  __/\/ / /  ___/\/  __/\/  __/\  / /_____\/
    / /_/ / / /_/ / / / / /\/ / /\/ /\_\/ / /  ___/\/\  \_\/\  \_\/ /______ \
   / ____/ /_____/ /_/ /_/ /_/ / /_/ / /_/ /_____/\/____/\/____/\/ _\_____/ /\
  /_/\___\/\_____\/\_\/\_\/\_\/  \_\/  \_\/\_____\/\____\/\____\/ /________/ /
  \_\/                                                            \_________/

EOF;

        IO::init()->notice($banner);
    }

    /**
     * Initialize Blog
     *
     * @return bool
     */
    public static function initBlog()
    {
        if (defined('BLOG_ROOT')) {
            return true;
        }

        // Define Blog Root
        if (false === file_exists(HOME_ROOT . '/default')) {
            file_put_contents(HOME_ROOT . '/default', '');

            return false;
        }

        $blogRoot = file_get_contents(HOME_ROOT . '/default');

        if (false === Utility::mkdir($blogRoot)) {
            file_put_contents(HOME_ROOT . '/default', '');

            return false;
        }

        define('BLOG_ROOT', $blogRoot);

        if (false === file_exists(BLOG_ROOT . '/config.php')) {
            copy(APP_ROOT . '/sample/config.php', BLOG_ROOT . '/config.php');
        }

        // Require Config Attr
        require BLOG_ROOT . '/config.php';

        Resource::set('system:config', $config);

        // Define Path
        define('BLOG_BUILD', BLOG_ROOT . '/build');
        define('BLOG_DEPLOY', BLOG_ROOT . '/deploy');
        define('BLOG_ASSET', BLOG_ROOT . '/assets');
        define('BLOG_HANDLER', BLOG_ROOT . '/handlers');
        define('BLOG_EXTENSION', BLOG_ROOT . '/extensions');
        define('BLOG_POST', BLOG_ROOT . '/posts');

        // Create Folders
        Utility::mkdir(BLOG_BUILD);
        Utility::mkdir(BLOG_DEPLOY);
        Utility::mkdir(BLOG_ASSET);
        Utility::mkdir(BLOG_HANDLER);
        Utility::mkdir(BLOG_EXTENSION);

        // Copy Post
        if (false === file_exists(BLOG_POST)) {
            Utility::copy(APP_ROOT . '/sample/posts', BLOG_POST);
        }

        // Init Theme
        if (false === file_exists(BLOG_ROOT . '/themes')) {
            Utility::copy(APP_ROOT . '/sample/themes', BLOG_ROOT . '/themes');
        }

        if ('' === $config['theme']) {
            $config['theme'] = 'Classic';
        }

        if (file_exists(BLOG_ROOT . "/themes/{$config['theme']}")) {
            define('BLOG_THEME', BLOG_ROOT . "/themes/{$config['theme']}");
        } else {
            define('BLOG_THEME', APP_ROOT . '/sample/themes/Classic');
        }

        // Set Timezone
        date_default_timezone_set($config['timezone']);

        // Fix Permission
        self::fixPermission(BLOG_ROOT);

        return true;
    }

    /**
     * Fix Permission
     *
     * @param string $path
     *
     * @return bool
     */
    public static function fixPermission($path)
    {
        if (false === is_string($path)) {
            return false;
        }

        // Check SERVER Variable
        if (false === isset($_SERVER['SUDO_USER'])) {
            return false;
        }

        // Change Owner (Fix Permission)
        Utility::chown($path, fileowner($_SERVER['HOME']), filegroup($_SERVER['HOME']));

        return true;
    }

    /**
     * Edit File
     *
     * @param string $path
     *
     * @return bool
     */
    public static function editFile($path)
    {
        if (false === is_string($path)) {
            return false;
        }

        $editor = Resource::get('system:config')['editor'];

        if (false === Utility::commandExists($editor)) {
            IO::init()->error("System command \"{$editor}\" is not found.");

            return false;
        }

        system("{$editor} {$path} < `tty` > `tty`");

        return true;
    }

    /**
     * Get Post List
     *
     * @param string $type
     * @param bool $withContent
     *
     * @return array
     */
    public static function getPostList($type = null, $withContent = false)
    {
        if (null !== $type && false === is_string($type)) {
            return false;
        }

        if (false === is_bool($withContent)) {
            return false;
        }

        $list = [];

        $parsedown = new Parsedown();
        $handle = opendir(BLOG_POST . "/{$type}");

        while ($filename = readdir($handle)) {
            if (!preg_match('/.md$/', $filename)) {
                continue;
            }

            $file = self::parseMarkdownFile($type, $filename, $withContent);

            if (false === $file) {
                IO::init()->error("Markdown parse error: {$filename}");

                exit(1);
            }

            if (null !== $type && $type !== $file['type']) {
                continue;
            }

            $file['name'] = $filename;
            $file['path'] = BLOG_POST . "/{$type}/{$filename}";

            if (false === $withContent) {
                $file['content'] = $parsedown->text($file['content']);
            }

            $index = (isset($file['date']) && isset($file['time']))
                ? "{$file['date']}{$file['time']}" : $file['title'];

            $list[$index] = $file;
        }

        closedir($handle);

        // Sort List
        uksort($list, 'strnatcasecmp');

        return array_values($list);
    }

    /**
     * Parse Markdown File
     *
     * @param string $type
     * @param string $filename
     * @param bool $withContent
     *
     * @return string
     */
    public static function parseMarkdownFile($type, $filename, $withContent = false)
    {
        if (false === is_string($filename)
            || false === is_bool($withContent)) {

            return false;
        }

        $filepath = BLOG_POST . "/{$type}/{$filename}";

        // Define Regular Expression Rule
        $regex = '/^(?:<!--({(?:.|\n)*})-->)\s*(?:#(.*))?((?:.|\n)*)/';

        preg_match($regex, file_get_contents($filepath), $match);

        if (4 !== count($match)) {
            return false;
        }

        $file = json_decode($match[1], true);

        if (null === $file) {
            return false;
        }

        $file['title'] = trim($match[2]);

        if (false === $withContent) {
            $file['content'] = trim($match[3]);
        }

        $file['accessTime'] = fileatime($filepath);
        $file['createTime'] = filectime($filepath);
        $file['modifyTime'] = filemtime($filepath);

        return $file;
    }

    /**
     * Get Theme List
     *
     * @return array
     */
    public static function getThemeList()
    {
        $list = [];

        $handle = opendir(BLOG_ROOT . '/themes');

        while ($filename = readdir($handle)) {
            if (in_array($filename, ['.', '..'])) {
                continue;
            }

            if (false === is_dir(BLOG_ROOT . "/themes/{$filename}")) {
                continue;
            }

            $list[$filename] = [
                'title' => $filename,
                'path' => BLOG_ROOT . "/themes/{$filename}"
            ];
        }

        closedir($handle);

        // Sort List
        uksort($list, 'strnatcasecmp');

        return array_values($list);
    }
}
