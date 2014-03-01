<?php
/**
 * Pointless Delete Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class DeleteCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    public function help()
    {
        IO::writeln('    delete <number or not>');
        IO::writeln('               - Delete article');
        IO::writeln('    delete -s <numebr ot not>');
        IO::writeln('               - Delete Static Page');
    }

    public function run()
    {
        if (!checkDefaultBlog()) {
            return false;
        }

        initBlog();

        $list = [];
        $handle = opendir(MARKDOWN);
        while ($filename = readdir($handle)) {
            if (!preg_match('/.md$/', $filename)) {
                continue;
            }

            preg_match(REGEX_RULE, file_get_contents(MARKDOWN . "/$filename"), $match);
            $post = json_decode($match[1], true);

            if ($this->hasOptions('s')) {
                if ('static' !== $post['type']) {
                    continue;
                }

                $index = $post['title'];

                $list[$index]['msg'] = $post['title'];
                $list[$index]['title'] = $post['title'];
                $list[$index]['path'] = MARKDOWN . "/$filename";
            } else {
                if ('article' !== $post['type']) {
                    continue;
                }

                $index = $post['date'] . $post['time'];

                $list[$index]['msg'] = "{$post['date']} {$post['title']}";
                $list[$index]['title'] = $post['title'];
                $list[$index]['path'] = MARKDOWN . "/$filename";
            }
        }
        closedir($handle);
        uksort($list, 'strnatcasecmp');

        if (0 === count($list)) {
            IO::writeln('No post(s).', 'red');

            return false;
        }

        $number = $this->getNumber();
        if ($number < 0 || $number >= count($list)) {
            $number = null;
        }

        if (null === $number) {
            $count = 0;
            foreach ($list as $post) {
                IO::writeln(sprintf("[%3d] ", $count) . $post['msg']);

                $count++;
            }

            $number = IO::question("\nEnter Number:\n-> ", null, function ($answer) use ($list) {
                return is_numeric($answer) && $answer >= 0 && $answer < count($list);
            });
        }

        $path = $list[array_keys($list)[$number]]['path'];
        $title = $list[array_keys($list)[$number]]['title'];
        IO::write("Are you sure delete post \"$title\"? [n/y]\n-> ", 'red');
        if ("y" === IO::read()) {
            system("rm $path");
            IO::writeln("Successfully removed post \"$title\".");
        }
    }

    private function getNumber()
    {
        if ($this->hasOptions('s')) {
            return $this->getOptions('s');
        }

        if ($this->hasArguments()) {
            return $this->getArguments()[0];
        }

        return null;
    }
}
