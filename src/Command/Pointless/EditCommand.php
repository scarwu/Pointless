<?php
/**
 * Pointless Edit Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;
use Resource;

class EditCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    public function help()
    {
        IO::writeln('    edit <number or not>');
        IO::writeln('               - Edit article');
        IO::writeln('    edit -s <number or not>');
        IO::writeln('               - Edit Static Page');
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

                $list[$index]['publish'] = $post['publish'];
                $list[$index]['msg'] = $post['title'];
                $list[$index]['path'] = MARKDOWN . "/$filename";
            } else {
                if ('article' !== $post['type']) {
                    continue;
                }

                $index = $post['date'] . $post['time'];

                $list[$index]['publish'] = $post['publish'];
                $list[$index]['msg'] = "{$post['date']} {$post['title']}";
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
                if ($post['publish']) {
                    IO::writeln(sprintf("[ %3d] ", $count) . $post['msg']);
                } else {
                    IO::writeln(sprintf("[*%3d] ", $count) . $post['msg']);
                }

                $count++;
            }

            $number = IO::question("\nEnter Number:\n-> ", null, function ($answer) use ($list) {
                return is_numeric($answer) && $answer >= 0 && $answer < count($list);
            });
        }

        $editor = Resource::get('config')['editor'];
        $path = $list[array_keys($list)[$number]]['path'];
        system("$editor $path < `tty` > `tty`");
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
