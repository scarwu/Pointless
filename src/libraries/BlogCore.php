<?php
/**
 * Blog Core
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Library;

use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Pointless\Library\CustomException;
use Parsedown;
use Oni\CLI\IO;

class BlogCore
{
    private function __construct() {}

    /**
     * Initialize
     *
     * @return bool
     */
    public static function init()
    {
        if (true === defined('BLOG_ROOT')) {
            return true;
        }

        // Load Config
        $blog = Utility::loadJsonFile(HOME_ROOT . '/blog.json');

        if (false === is_array($blog)
            || false === is_string($blog['path'])
        ) {
            return false;
        }

        if (false === Utility::mkdir($blog['path'])) {
            return false;
        }

        // Define Variables
        define('BLOG_ROOT', $blog['path']);
        define('BLOG_BUILD', BLOG_ROOT . '/build');
        define('BLOG_DEPLOY', BLOG_ROOT . '/deploy');
        define('BLOG_ASSET', BLOG_ROOT . '/assets');
        define('BLOG_HANDLER', BLOG_ROOT . '/handlers');
        define('BLOG_EXTENSION', BLOG_ROOT . '/extensions');
        define('BLOG_POST', BLOG_ROOT . '/posts');

        require BLOG_ROOT . '/config.php';

        if (false === isset($config['theme']) || '' === $config['theme']) {
            $config['theme'] = 'Classic';
        }

        if (false === isset($config['timezone']) || '' === $config['timezone']) {
            $config['timezone'] = 'Etc/UTC';
        }

        if (true === file_exists(BLOG_ROOT . "/themes/{$config['theme']}")) {
            define('BLOG_THEME', BLOG_ROOT . "/themes/{$config['theme']}");
        } else {
            define('BLOG_THEME', APP_ROOT . '/sample/themes/Classic');
        }

        // Set Timezone
        date_default_timezone_set($config['timezone']);

        Resource::set('system:config', $config);

        // Copy Sample Files
        if (false === file_exists(BLOG_ROOT . '/config.php')) {
            Utility::copy(APP_ROOT . '/sample/config.php', BLOG_ROOT . '/config.php');
        }

        if (false === file_exists(BLOG_POST)) {
            Utility::copy(APP_ROOT . '/sample/posts', BLOG_POST);
        }

        if (false === file_exists(BLOG_ROOT . '/themes')) {
            Utility::copy(APP_ROOT . '/sample/themes', BLOG_ROOT . '/themes');
        }

        // Create Folders
        Utility::mkdir(BLOG_BUILD);
        Utility::mkdir(BLOG_DEPLOY);
        Utility::mkdir(BLOG_ASSET);
        Utility::mkdir(BLOG_HANDLER);
        Utility::mkdir(BLOG_EXTENSION);

        // Fix Permission
        Utility::fixPermission(BLOG_ROOT);

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
    public static function parseMarkdownFile(string $type, string $filename, bool $withContent = false)
    {
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
    public static function getThemeList(): array
    {
        $list = [];

        $handle = opendir(BLOG_ROOT . '/themes');

        while ($filename = readdir($handle)) {
            if (true === in_array($filename, ['.', '..'])) {
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
