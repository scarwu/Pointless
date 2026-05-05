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
    public function helpInfo(): void
    {
        $this->io->log('blog init <path?>       - Init blog');
    }

    /**
     * Lifecycle Funtions
     */
    #[\Override]
    public function run(array $params = []): void
    {
        // [ 'blog', 'init', '<path>' ]
        $path = (null !== $this->io->getArguments(2))
            ? $this->io->getArguments(2) : '';

        if (!str_starts_with($path, '/') || strlen($path) <= 1) {
            $path = getcwd() . ('' !== $path ? "/{$path}" : $path);
        }

        // Load Blog Config
        $blog = Utility::loadJsonFile(HOME_ROOT . '/blog.json');

        if (!is_array($blog)) {
            $blog = [];
        }

        $blog['path'] = $path;

        Utility::saveJsonFile(HOME_ROOT . '/blog.json', $blog);

        // Init Blog
        if (false === BlogCore::init()) {
            $this->io->error('Init Blog Failed.');

            return;
        }

        $this->io->notice("Default blog is setting to path \"{$path}\".");
    }
}
