<?php
/**
 * Pointless Post Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2016, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main;

use Misc,
    NanoCLI\Command,
    NanoCLI\IO;

class PostCommand extends Command
{
    public function help()
    {
        IO::log('    post        - Show post status');
        IO::log('    post add    - Add new post');
        IO::log('    post edit   - Edit post');
        IO::log('    post delete - Delete post');
    }

    public function up()
    {
        if (!Misc::checkDefaultBlog()) {
            return false;
        }

        Misc::initBlog();
    }

    public function run()
    {
        // Load Doctype
        $type = [];
        $handle = opendir(APP_ROOT . '/doctype');

        while ($filename = readdir($handle)) {
            if (!preg_match('/.php$/', $filename)) {
                continue;
            }

            $filename = preg_replace('/.php$/', '', $filename);

            require APP_ROOT . "/doctype/{$filename}.php";

            $temp = new $filename;
            $type[$temp->getID()] = $temp;
        }

        closedir($handle);

        IO::writeln();

        // Load Markdown
        $list = [];
        $handle = opendir(BLOG_MARKDOWN);

        while ($filename = readdir($handle)) {
            if (!preg_match('/.md$/', $filename)) {
                continue;
            }

            $post = parseMarkdownFile($filename, true);

            if (!$post) {
                IO::error("Markdown parse error: {$filename}");

                exit(1);
            }

            if (!isset($list[$post['type']])) {
                $list[$post['type']] = 0;
            }

            $list[$post['type']]++;
        }

        closedir($handle);

        IO::notice('Post Status:');

        foreach ($list as $key => $value) {
            if (isset($type[$key])) {
                $value = sprintf('%3d', $value);
                $name = $type[$key]->getName();

                IO::log("{$value} {$name} post(s).");
            }
        }

        IO::info("\nUsed command \"help post\" for more.");
    }
}
