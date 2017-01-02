<?php
/**
 * Pointless Initialize Home Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main\Home;

use Pointless\Library\Misc;
use NanoCLI\Command;
use NanoCLI\IO;

class InitCommand extends Command
{

    /**
     * @var string
     */
    private $path;

    /**
     * Help
     */
    public function help()
    {
        IO::log('    home init <path or not>');
        IO::log('                - Init a new blog');
    }

    /**
     * Up
     */
    public function up()
    {
        $this->path = $this->getPath();

        if (file_exists($this->path)) {
            IO::error("Path \"{$this->path}\" is exists.");

            return false;
        }

        if (!mkdir($this->path, 0755, true)) {
            IO::error("Permission denied: {$this->path}");

            return false;
        }
    }

    /**
     * Run
     */
    public function run()
    {
        define('BLOG', $this->path);

        file_put_contents(HOME_ROOT . '/default', BLOG);

        Misc::initBlog();

        IO::notice('Blog is initialized.');
        IO::notice("Default blog is setting to path \"{$this->path}\".");
    }

    /**
     * Gte Path
     *
     * @return string
     */
    private function getPath()
    {
        $path = '';

        if ($this->getArguments(0)) {
            $path = $this->getArguments(0);
        }

        if (!preg_match('/^\/(.+)/', $path)) {
            $path = getcwd() . ('' !== $path ? "/{$path}" : $path);
        }

        return $path;
    }
}
