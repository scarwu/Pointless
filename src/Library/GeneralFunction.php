<?php
/**
 * General Function
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

/**
 * Check Default Blog
 */
function checkDefaultBlog()
{
    $msg = 'Default blog is\'t set. Please use command "home -s" or "home -i".';

    if (!file_exists(HOME . '/Default')) {
        IO::writeln($msg, 'red');

        return false;
    }

    $path = file_get_contents(HOME . '/Default');

    if ('' === $path) {
        IO::writeln($msg, 'red');

        return false;
    }

    if (!file_exists($path) || !file_exists("$path/.pointless")) {
        file_put_contents(HOME . '/defualt', '');

        IO::writeln($msg, 'red');

        return false;
    }

    define('BLOG', $path);

    return true;
}

/**
 * Initialize Blog
 */
function initBlog()
{
    if (!file_exists(BLOG . '/.pointless')) {
        file_put_contents(BLOG . '/.pointless', '');
    }

    if (!file_exists(BLOG . '/Config.php')) {
        copy(ROOT . '/Sample/Config.php', BLOG . '/Config.php');
    }

    // Require Config
    require BLOG . '/Config.php';
    Resource::set('config', $config);

    // Define Path
    define('TEMP', BLOG . '/Temp');
    define('DEPLOY', BLOG . '/Deploy');
    define('RESOURCE', BLOG . '/Resource');
    define('EXTENSION', BLOG . '/Extension');
    define('MARKDOWN', BLOG . '/Markdown');

    if (!file_exists(TEMP)) {
        mkdir(TEMP, 0755);
    }

    if (!file_exists(DEPLOY)) {
        mkdir(DEPLOY, 0755);
    }

    if (!file_exists(RESOURCE)) {
        mkdir(RESOURCE, 0755);
    }

    if (!file_exists(EXTENSION)) {
        mkdir(EXTENSION, 0755);
    }

    if (!file_exists(MARKDOWN)) {
        recursiveCopy(ROOT . '/Sample/Markdown', MARKDOWN);
    }

    if (!file_exists(BLOG . '/Theme')) {
        recursiveCopy(ROOT . '/Sample/Theme', BLOG . '/Theme');
    }

    if ('' === $config['theme']) {
        $config['theme'] = 'Classic';
    }

    if (file_exists(BLOG . "/Theme/{$config['theme']}")) {
        define('THEME', BLOG . "/Theme/{$config['theme']}");
    } else {
        define('THEME', ROOT . '/Sample/Theme/Classic');
    }

    // Set Timezone
    date_default_timezone_set($config['timezone']);

    // Change Owner
    if (isset($_SERVER['SUDO_USER'])) {
        $user = fileowner(HOME);
        $group = filegroup(HOME);
        system("chown $user.$group -R " . BLOG);
    }
}

/**
 * Bind PHP Data to HTML Template
 *
 * @param string
 * @param string
 * @return string
 */
function bindData($_data, $_path)
{
    foreach ($_data as $_key => $_value) {
        $$_key = $_value;
    }

    ob_start();
    include $_path;
    $_result = ob_get_contents();
    ob_end_clean();

    return $_result;
}

/**
 * Write Data to File
 *
 * @param string
 * @param string
 */
function writeTo($data, $path)
{
    if (!preg_match('/\.(html|xml)$/', $path)) {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $path = "$path/index.html";
    } else {
        $segments = explode('/', $path);
        array_pop($segments);

        $dirpath = implode($segments, '/');
        if (!file_exists($dirpath)) {
            mkdir($dirpath, 0755, true);
        }
    }

    $handle = fopen($path, 'w+');
    fwrite($handle, $data);
    fclose($handle);
}

/**
 * Recursive Copy
 *
 * @param string
 * @param string
 */
function recursiveCopy($src, $dest)
{
    if (file_exists($src)) {
        if (is_dir($src)) {
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }

            $handle = opendir($src);
            while ($file = readdir($handle)) {
                if (!in_array($file, ['.', '..', '.git'])) {
                    recursiveCopy("$src/$file", "$dest/$file");
                }
            }
            closedir($handle);
        } else {
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
function recursiveRemove($path = null, $self = null)
{
    if (file_exists($path)) {
        if (is_dir($path)) {
            $handle = opendir($path);
            while ($file = readdir($handle)) {
                if (!in_array($file, ['.', '..', '.git'])) {
                    recursiveRemove("$path/$file");
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
