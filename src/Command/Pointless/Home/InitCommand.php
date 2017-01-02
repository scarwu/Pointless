<?php
/**
 * Pointless Initialize Home Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Home;

use NanoCLI\Command;
use NanoCLI\IO;

class InitCommand extends Command
{
    private $path;

    public function help()
    {
        IO::log('    home init <path or not>');
        IO::log('                - Init a new blog');
    }

    public function up()
    {
        $this->path = $this->getPath();

        if (file_exists($this->path)) {
            IO::error("Path \"$this->path\" is exists.");

            return false;
        }

        if (!mkdir($this->path, 0755, true)) {
            IO::error("Permission denied: $this->path");

            return false;
        }
    }

    public function run()
    {
        define('BLOG', $this->path);
        file_put_contents(HOME . '/Default', BLOG);

        initBlog();

        IO::notice('Blog is initialized.');
        IO::notice("Default blog is setting to path \"$this->path\".");
    }

    private function getPath()
    {
        $path = '';

        if ($this->getArguments(0)) {
            $path = $this->getArguments(0);
        }

        if (!preg_match('/^\/(.+)/', $path)) {
            $path = getcwd() . ('' !== $path ? "/$path" : $path);
        }

        return $path;
    }
}
