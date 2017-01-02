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

use Pointless\Library\Utility;
use Pointless\Library\Resource;
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
        if (!file_exists(HOME_ROOT . '/default')) {
            file_put_contents(HOME_ROOT . '/defualt', '');

            return false;
        }

        $blog_root = file_get_contents(HOME_ROOT . '/default');

        if (!Utility::mkdir($blog_root)) {
            file_put_contents(HOME_ROOT . '/defualt', '');

            return false;
        }

        define('BLOG_ROOT', $blog_root);

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
        Utility::mkdir(BLOG_BUILD);
        Utility::mkdir(BLOG_DEPLOY);
        Utility::mkdir(BLOG_STATIC);
        Utility::mkdir(BLOG_EXTENSION);

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

        // Fix Permission
        self::fixPermission(BLOG_ROOT);

        return true;
    }

    /**
     * Fix Permission
     *
     * @param string
     *
     * @return boolean
     */
    public static function fixPermission($path) {

        if (!isset($_SERVER['SUDO_USER'])) {
            return false;
        }

        // Change Owner (Fix Permission)
        Utility::chown($path, fileowner($_SERVER['HOME']), filegroup($_SERVER['HOME']));

        return true;
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
