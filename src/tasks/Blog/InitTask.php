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

use Pointless\Library\BlogCore;
use Pointless\Library\Utility;
use Pointless\Extend\Task;

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
        $this->io->log('    blog init <path?>       - Init blog');
    }

    /**
     * Lifecycle Funtions
     */
    public function run()
    {
        // [ 'blog', 'init', '<path>' ]
        $path = (null !== $this->io->getArguments(2))
            ? $this->io->getArguments(2) : '';

        if (false === (bool) preg_match('/^\/(.+)/', $path)) {
            $path = getcwd() . ('' !== $path ? "/{$path}" : $path);
        }

        // Load Config
        $config = Utility::loadJsonFile(HOME_ROOT . '/config.json');

        if (false === is_array($config)) {
            $config = [];
        }

        $config['blog'] = $path;

        Utility::saveJsonFile(HOME_ROOT . '/config.json', $config);

        // Init Blog
        if (false === BlogCore::init()) {
            $this->io->error('Init Blog Failed.');

            return false;
        }

        $this->io->notice("Default blog is setting to path \"{$path}\".");
    }
}
