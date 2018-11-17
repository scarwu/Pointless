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
                                           __                              __
      ______  ______  __  ______  ______  / /\______  _____  _____    __  / /\
     / __  /\/ __  /\/ /\/ __  /\/_  __/\/ / /  ___/\/  __/\/  __/\  / /\/ / /
    / /_/ / / /_/ / / / / /\/ / /\/ /\_\/ / /  ___/\/\  \_\/\  \_\/ / /_/ /_/
   / ____/ /_____/ /_/ /_/ /_/ / /_/ / /_/ /_____/\/____/\/____/\/ /___  __/\
  /_/\___\/\_____\/\_\/\_\/\_\/  \_\/  \_\/\_____\/\____\/\____\/  \__/_/\_\/
  \_\/                                                                \_\/

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

        if (false === file_exists(BLOG_ROOT . '/.pointless')) {
            file_put_contents(BLOG_ROOT . '/.pointless', '');
        }

        if (false === file_exists(BLOG_ROOT . '/config.php')) {
            copy(APP_ROOT . '/sample/config.php', BLOG_ROOT . '/config.php');
        }

        // Require Config Attr
        require BLOG_ROOT . '/config.php';

        Resource::set('system:config', $config);

        // Define Path
        define('BLOG_BUILD', BLOG_ROOT . '/build');
        define('BLOG_DEPLOY', BLOG_ROOT . '/deploy');
        define('BLOG_STATIC', BLOG_ROOT . '/static');
        define('BLOG_HANDLER', BLOG_ROOT . '/handlers');
        define('BLOG_EXTENSION', BLOG_ROOT . '/extensions');
        define('BLOG_POST', BLOG_ROOT . '/posts');

        // Create Folders
        Utility::mkdir(BLOG_BUILD);
        Utility::mkdir(BLOG_DEPLOY);
        Utility::mkdir(BLOG_STATIC);
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
        $handle = opendir(BLOG_POST);

        while ($filename = readdir($handle)) {
            if (!preg_match('/.md$/', $filename)) {
                continue;
            }

            $post = self::parseMarkdownFile($filename, $withContent);

            if (false === $post) {
                IO::init()->error("Markdown parse error: {$filename}");

                exit(1);
            }

            if (null !== $type && $type !== $post['type']) {
                continue;
            }

            $post['path'] = BLOG_POST . "/{$filename}";

            if (false === $withContent) {
                $post['content'] = $parsedown->text($post['content']);
            }

            $index = (isset($post['date']) && isset($post['time']))
                ? "{$post['date']}{$post['time']}" : $post['title'];

            $list[$index] = $post;
        }

        closedir($handle);

        // Sort List
        uksort($list, 'strnatcasecmp');

        return array_values($list);
    }

    /**
     * Parse Markdown File
     *
     * @param string $filename
     * @param bool $withContent
     *
     * @return string
     */
    public static function parseMarkdownFile($filename, $withContent = false)
    {
        if (false === is_string($filename)
            || false === is_bool($withContent)) {

            return false;
        }

        // Define Regular Expression Rule
        $regex = '/^(?:<!--({(?:.|\n)*})-->)\s*(?:#(.*))?((?:.|\n)*)/';

        preg_match($regex, file_get_contents(BLOG_POST . "/{$filename}"), $match);

        if (4 !== count($match)) {
            return false;
        }

        $post = json_decode($match[1], true);

        if (null === $post) {
            return false;
        }

        $post['title'] = trim($match[2]);

        if (false === $withContent) {
            $post['content'] = trim($match[3]);
        }

        $post['accessTime'] = fileatime(BLOG_POST . "/{$filename}");
        $post['createTime'] = filectime(BLOG_POST . "/{$filename}");
        $post['modifyTime'] = filemtime(BLOG_POST . "/{$filename}");

        return $post;
    }
}
