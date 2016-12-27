<?php
/**
 * Miscellaneous
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2016, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Library;

use NanoCLI\IO;

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

        IO::notice($banner);
    }

    /**
     * Check Default Blog
     */
    public static function checkDefaultBlog()
    {
        $msg = "Default blog is't set.\nPlease use command \"home set\" or \"home init\".";

        if (!file_exists(APP_HOME . '/default')) {
            IO::error($msg);

            return false;
        }

        $path = file_get_contents(APP_HOME . '/default');

        if ('' === $path) {
            IO::error($msg);

            return false;
        }

        if (!file_exists($path) || !file_exists("{$path}/.pointless")) {
            file_put_contents(APP_HOME . '/defualt', '');

            IO::error($msg);

            return false;
        }

        define('BLOG', $path);

        return true;
    }

    /**
     * Initialize Blog
     */
    public static function initBlog()
    {
        if (!file_exists(BLOG . '/.pointless')) {
            file_put_contents(BLOG . '/.pointless', '');
        }

        if (!file_exists(BLOG . '/config.php')) {
            copy(APP_ROOT . '/sample/config.php', BLOG . '/config.php');
        }

        // Require Config
        require BLOG . '/config.php';

        Resource::set('config', $config);

        // Define Path
        define('BLOG_TEMP', BLOG . '/temp');
        define('BLOG_DEPLOY', BLOG . '/deploy');
        define('BLOG_RESOURCE', BLOG . '/resource');
        define('BLOG_EXTENSION', BLOG . '/extensions');
        define('BLOG_MARKDOWN', BLOG . '/markdown');

        if (!file_exists(BLOG_TEMP)) {
            mkdir(BLOG_TEMP, 0755);
        }

        if (!file_exists(BLOG_DEPLOY)) {
            mkdir(BLOG_DEPLOY, 0755);
        }

        if (!file_exists(BLOG_RESOURCE)) {
            mkdir(BLOG_RESOURCE, 0755);
        }

        if (!file_exists(BLOG_EXTENSION)) {
            mkdir(BLOG_EXTENSION, 0755);
        }

        if (!file_exists(BLOG_MARKDOWN)) {
            Utility::copy(APP_ROOT . '/sample/markdown', BLOG_MARKDOWN);
        }

        if (!file_exists(BLOG . '/theme')) {
            Utility::copy(APP_ROOT . '/sample/theme', BLOG . '/theme');
        }

        if ('' === $config['theme']) {
            $config['theme'] = 'Classic';
        }

        if (file_exists(BLOG . "/theme/{$config['theme']}")) {
            define('BLOG_THEME', BLOG . "/theme/{$config['theme']}");
        } else {
            define('BLOG_THEME', APP_ROOT . '/sample/theme/Classic');
        }

        // Set Timezone
        date_default_timezone_set($config['timezone']);

        // Change Owner
        if (isset($_SERVER['SUDO_USER'])) {
            Utility::chown(BLOG, fileowner(APP_HOME), filegroup(APP_HOME));
        }
    }

    /**
     * Parse Markdown File
     *
     * @param string filename
     * @param boolean is_skip_content
     *
     * @return string
     */
    public static function parseMarkdownFile($filename, $is_skip_content = false)
    {
        // Define Regular Expression Rule
        $regex = '/^(?:<!--({(?:.|\n)*})-->)\s*(?:#(.*))?((?:.|\n)*)/';

        preg_match($regex, file_get_contents(BLOG_MARKDOWN . "/$filename"), $match);

        if (4 !== count($match)) {
            return false;
        }

        if (null === ($post = json_decode($match[1], true))) {
            return false;
        }

        $post['title'] = trim($match[2]);

        if (!$is_skip_content) {
            $post['content'] = $match[3];
        }

        return $post;
    }
}
