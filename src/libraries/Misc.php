<?php
/**
 * Miscellaneous
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
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
     * Check Blog
     *
     * @return boolean
     */
    public static function checkBlog()
    {
        if (!file_exists(HOME_ROOT . '/default')) {
            IO::error($msg);

            return false;
        }

        $path = file_get_contents(HOME_ROOT . '/default');

        if (!file_exists($path) || !file_exists("{$path}/.pointless")) {

            // Reset Default Path
            file_put_contents(HOME_ROOT . '/defualt', '');

            IO::error("Default blog is't set.");
            IO::error("Please use command \"home set\" or \"home init\".");

            return false;
        }

        define('BLOG_ROOT', $path);

        return true;
    }

    /**
     * Initialize Blog
     *
     * @return boolean
     */
    public static function initBlog()
    {
        if (!file_exists(BLOG_ROOT . '/.pointless')) {
            file_put_contents(BLOG_ROOT . '/.pointless', '');
        }

        if (!file_exists(BLOG_ROOT . '/config.php')) {
            copy(APP_ROOT . '/sample/config.php', BLOG_ROOT . '/config.php');
        }

        // Require Config
        require BLOG_ROOT . '/config.php';

        Resource::set('config', $config);

        // Define Path
        define('BLOG_BUILD', BLOG_ROOT . '/build');
        define('BLOG_DEPLOY', BLOG_ROOT . '/deploy');
        define('BLOG_STATIC', BLOG_ROOT . '/static');
        define('BLOG_EXTENSION', BLOG_ROOT . '/extensions');
        define('BLOG_MARKDOWN', BLOG_ROOT . '/markdown');

        // Create Folders
        if (!file_exists(BLOG_BUILD)) {
            mkdir(BLOG_BUILD, 0755);
        }

        if (!file_exists(BLOG_DEPLOY)) {
            mkdir(BLOG_DEPLOY, 0755);
        }

        if (!file_exists(BLOG_STATIC)) {
            mkdir(BLOG_STATIC, 0755);
        }

        if (!file_exists(BLOG_EXTENSION)) {
            mkdir(BLOG_EXTENSION, 0755);
        }

        // Copy Markdown
        if (!file_exists(BLOG_MARKDOWN)) {
            Utility::copy(APP_ROOT . '/sample/markdown', BLOG_MARKDOWN);
        }

        // Init Theme
        if (!file_exists(BLOG_ROOT . '/theme')) {
            Utility::copy(APP_ROOT . '/sample/theme', BLOG_ROOT . '/theme');
        }

        if ('' === $config['theme']) {
            $config['theme'] = 'Classic';
        }

        if (file_exists(BLOG_ROOT . "/theme/{$config['theme']}")) {
            define('BLOG_THEME', BLOG_ROOT . "/theme/{$config['theme']}");
        } else {
            define('BLOG_THEME', APP_ROOT . '/sample/theme/Classic');
        }

        // Set Timezone
        date_default_timezone_set($config['timezone']);

        // Change Owner
        if (IS_SUPER_USER) {
            Utility::chown(BLOG_ROOT, fileowner(HOME_ROOT), filegroup(HOME_ROOT));
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

        preg_match($regex, file_get_contents(BLOG_MARKDOWN . "/{$filename}"), $match);

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
