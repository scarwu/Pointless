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
                                           __
      ______  ______  __  ______  ______  / /\______  _____  _____
     / __  /\/ __  /\/ /\/ __  /\/_  __/\/ / /  ___/\/  __/\/  __/\
    / /_/ / / /_/ / / / / /\/ / /\/ /\_\/ / /  ___/\/\  \_\/\  \_\/
   / ____/ /_____/ /_/ /_/ /_/ / /_/ / /_/ /_____/\/____/\/____/\
  /_/\___\/\_____\/\_\/\_\/\_\/  \_\/  \_\/\_____\/\____\/\____\/
  \_\/

EOF;

        IO::init()->notice($banner);
    }

    /**
     * Initialize Blog
     *
     * @return boolean
     */
    public static function initBlog()
    {
        if (defined('BLOG_ROOT')) {
            return true;
        }

        // Define Blog Root
        if (false === file_exists(HOME_ROOT . '/default')) {
            file_put_contents(HOME_ROOT . '/defualt', '');

            return false;
        }

        $blog_root = file_get_contents(HOME_ROOT . '/default');

        if (false === Utility::mkdir($blog_root)) {
            file_put_contents(HOME_ROOT . '/defualt', '');

            return false;
        }

        define('BLOG_ROOT', $blog_root);

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
        define('BLOG_EXTENSION', BLOG_ROOT . '/extensions');
        define('BLOG_POST', BLOG_ROOT . '/posts');

        // Create Folders
        Utility::mkdir(BLOG_BUILD);
        Utility::mkdir(BLOG_DEPLOY);
        Utility::mkdir(BLOG_STATIC);
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
     * @return boolean
     */
    public static function fixPermission($path)
    {
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
     * @return boolean
     */
    public static function editFile($path)
    {
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
     * @param boolean $skipContent
     *
     * @return array
     */
    public static function getPostList($type = null, $skipContent = false)
    {
        $list = [];

        $parsedown = new Parsedown();
        $handle = opendir(BLOG_POST);

        while ($filename = readdir($handle)) {
            if (false === preg_match('/.md$/', $filename)) {
                continue;
            }

            $post = self::parseMarkdownFile($filename, $skipContent);

            if (false === $post) {
                IO::init()->error("Markdown parse error: {$filename}");

                exit(1);
            }

            if ($type !== null && $type !== $post['type']) {
                continue;
            }

            $post['path'] = BLOG_POST . "/{$filename}";

            if (false === $skipContent) {
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
     * @param boolean $skipContent
     *
     * @return string
     */
    public static function parseMarkdownFile($filename, $skipContent = false)
    {
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

        if (false === $skipContent) {
            $post['content'] = $match[3];
        }

        return $post;
    }
}
