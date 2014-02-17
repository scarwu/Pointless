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
use Resource;

class DeleteCommand extends Command {
    public function __construct() {
        parent::__construct();
    }

    public function help() {
        IO::writeln('    delete     - Delete article');
        IO::writeln('    delete -s  - Delete Static Page');
    }

    public function run() {
        $config = Resource::get('config');

        $data = [];
        $handle = opendir(MARKDOWN);
        while($filename = readdir($handle)) {
            if('.' == $filename || '..' == $filename || !preg_match('/.md$/', $filename))
                continue;

            preg_match(REGEX_RULE, file_get_contents(MARKDOWN . "/$filename"), $match);
            $temp = json_decode($match[1], TRUE);

            if($this->hasOptions('s')) {
                if('static' != $temp['type'])
                    continue;

                $data[$temp['title']]['title'] = $temp['title'];
                $data[$temp['title']]['path'] = MARKDOWN . "/$filename";
            }
            else {
                if('article' != $temp['type'])
                    continue;

                $index = $temp['date'] . $temp['time'];

                $data[$index]['title'] = $temp['title'];
                $data[$index]['date'] = $temp['date'];
                $data[$index]['path'] = MARKDOWN . "/$filename";
            }
        }
        closedir($handle);

        uksort($data, 'strnatcasecmp');

        $count = 0;
        foreach($data as $key => $article) {
            if($this->hasOptions('s')) {
                $msg = $article['title'];
            }
            else {
                $msg = "{$article['date']} {$article['title']}";
            }

            IO::writeln(sprintf("[%3d] ", $count) . $msg);

            $data[$count++] = $article;
            unset($data[$key]);
        }
        
        $number = IO::question("\nEnter Number:\n-> ", NULL, function($answer) use($data) {
            return is_numeric($answer) && $answer >= 0 && $answer < count($data);
        });

        IO::write(sprintf("Are you sure delete - %s? [n/y]\n-> ", $data[$number]['title']), 'red');
        if(IO::read() == "y") {
            system('rm ' . $data[$number]['path']);
            IO::writeln(sprintf('Successfully removed %s.', $data[$number]['title']));
        }
    }
}
