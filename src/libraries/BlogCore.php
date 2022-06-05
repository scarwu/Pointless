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

use Parsedown;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Pointless\Library\CustomException;
use Oni\CLI\IO;

class BlogCore
{
    private function __construct() {}

    private static $_isInited = false;

    /**
     * @var object
     */
    private static $_parsedown = null;

    /**
     * Initialize
     *
     * @return bool
     */
    public static function init()
    {
        if (true === self::$_isInited) {
            return true;
        }

        if (false === getenv('BLOG_ROOT')) {

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

            define('BLOG_ROOT', $blog['path']);
        } else {
            define('BLOG_ROOT', getenv('BLOG_ROOT'));
        }

        // Define Variables
        define('BLOG_BUILD', BLOG_ROOT . '/build');
        define('BLOG_DEPLOY', BLOG_ROOT . '/deploy');
        define('BLOG_BACKUP', BLOG_ROOT . '/backup');
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

        if (false === getenv('BLOG_THEME')) {
            if (true === file_exists(BLOG_ROOT . "/themes/{$config['theme']}")) {
                define('BLOG_THEME', BLOG_ROOT . "/themes/{$config['theme']}");
            } else {
                define('BLOG_THEME', APP_ROOT . '/sample/themes/Classic');
            }
        } else {
            define('BLOG_THEME', getenv('BLOG_THEME'));
        }

        if (false === getenv('BLOG_EDITOR')) {
            define('BLOG_EDITOR', APP_ROOT . '/sample/editor');
        } else {
            define('BLOG_EDITOR', getenv('BLOG_EDITOR'));
        }

        // Set Timezone
        date_default_timezone_set($config['timezone']);

        Resource::set('blog:config', $config);

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
        Utility::mkdir(BLOG_BACKUP);
        Utility::mkdir(BLOG_ASSET);
        Utility::mkdir(BLOG_HANDLER);
        Utility::mkdir(BLOG_EXTENSION);

        // Fix Permission
        Utility::fixPermission(BLOG_ROOT);

        // Require Theme Config & Constant
        require BLOG_THEME . '/config.php';
        require BLOG_THEME . '/constant.php';

        Resource::set('theme:config', $config);
        Resource::set('theme:constant', $constant);

        self::$_isInited = true;

        return true;
    }

    /**
     * Get Post List
     *
     * @param string $type
     * @param bool $isParseRaw
     *
     * @return array
     */
    public static function getPostList(?string $type = null, bool $isParseRaw = false)
    {
        $list = [];
        $handle = opendir(BLOG_POST . "/{$type}");

        while ($filename = readdir($handle)) {
            if (false === (bool) preg_match('/.md$/', $filename)) {
                continue;
            }

            $filepath = BLOG_POST . "/{$type}/{$filename}";

            // Load Markdown File
            $markdown = Utility::loadMarkdownFile($filepath);

            if (null === $markdown) {
                throw new CustomException('blogCore:getPostList:loadMarkdownFile:error');
            }

            if (true === $isParseRaw) {
                $parseResult = self::parseMarkdownRaw($markdown['raw']);

                if (false === is_array($parseResult)) {
                    throw new CustomException('blogCore:getPostList:parseMarkdownRaw:error');
                }

                $markdown['title'] = $parseResult['title'];
                $markdown['content'] = $parseResult['content'];

                // unset($markdown['raw']);
            }

            $markdown['filename'] = $filename;
            $markdown['filepath'] = $filepath;
            $markdown['accessTime'] = fileatime($markdown['filepath']);
            $markdown['createTime'] = filectime($markdown['filepath']);
            $markdown['modifyTime'] = filemtime($markdown['filepath']);

            $key = (true === isset($markdown['params']['date']) && true === isset($markdown['params']['time']))
                ? "{$markdown['params']['date']} {$markdown['params']['time']}"
                : $markdown['filename'];

            $list[$key] = $markdown;
        }

        closedir($handle);

        // Sort List
        uksort($list, 'strnatcasecmp');

        return array_values($list);
    }

    /**
     * Parse Markdown Raw
     *
     * @param string $raw
     *
     * @return array
     */
    public static function parseMarkdownRaw(string $raw): ?array
    {
        if (null === self::$_parsedown) {
            self::$_parsedown = new Parsedown();
        }

        $regex = '/^(?:#(.*))?((?:.|\s)*)/';

        if (false === (bool) preg_match($regex, $raw, $match)) {
            return null;
        }

        return [
            'title' => trim($match[1]),
            'content' => self::$_parsedown->text($match[2])
        ];
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
