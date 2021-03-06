<?php
/**
 * Blog Init Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Task\Blog;

use Pointless\Library\Misc;
use Oni\CLI\Task;

class InitTask extends Task
{
    /**
     * @var string
     */
    private $path;

    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('    blog init <path or not>');
        $this->io->log('                        - Init a new blog');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        $this->path = $this->getPath();

        if (file_exists($this->path)) {
            $this->io->error("Path \"{$this->path}\" is exists.");

            return false;
        }
    }

    public function run()
    {
        // Set Path to Defult Blog File
        file_put_contents(HOME_ROOT . '/default', $this->path);

        // Init Blog
        if (false === Misc::initBlog()) {
            return false;
        }

        $this->io->notice("Default blog is setting to path \"{$this->path}\".");
    }

    /**
     * Gte Path
     *
     * @return string
     */
    private function getPath()
    {
        $path = '';

        // [ 'blog', 'init', '<path>' ]
        if ($this->io->getArguments(2)) {
            $path = $this->io->getArguments(2);
        }

        if (!preg_match('/^\/(.+)/', $path)) {
            $path = getcwd() . ('' !== $path ? "/{$path}" : $path);
        }

        return $path;
    }
}
