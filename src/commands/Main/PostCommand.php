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
use NanoCLI\Command;
use NanoCLI\IO;

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
        // Get Doctype List
        $doctype_list = Misc::getDoctypeList();

        IO::notice('Post Status:');

        foreach ($doctype_list as $doctype) {
            $count = count(Misc::getMarkdownList($doctype));

            $class_name = 'Pointless\\Doctype\\' . ucfirst($doctype) . 'Doctype';
            $name = (new $class_name)->getName();

            IO::log("{$count} {$name} post(s).");
        }

        IO::info("\nUsed command \"help post\" for more.");
    }
}
