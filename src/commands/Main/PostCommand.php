<?php
/**
 * Pointless Post Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main;

use Pointless\Library\Misc;
use Pointless\Library\Resource;
use Oni\CLI\Command;
use Oni\CLI\IO;

class PostCommand extends Command
{
    /**
     * Help
     */
    public function help()
    {
        IO::log('    post        - Show post status');
        IO::log('    post add    - Add new post');
        IO::log('    post edit   - Edit post');
        IO::log('    post delete - Delete post');
    }

    /**
     * Up
     */
    public function up()
    {
        // Init Blog
        if (!Misc::initBlog()) {
            return false;
        }
    }

    /**
     * Run
     */
    public function run()
    {
        IO::notice('Post Status:');

        foreach (Resource::get('attr:constant')['formats'] as $subClassName) {
            $className = 'Pointless\\Format\\' . ucfirst($subClassName);
            $format = new $className;

            $name = $format->getName();
            $type = $format->getType();
            $count = count(Misc::getPostList($type));

            IO::log("{$count} {$name} post(s).");
        }

        IO::writeln();
        IO::info('Used command "help post" for more.');
    }
}
