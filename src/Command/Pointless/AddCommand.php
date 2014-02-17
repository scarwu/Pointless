<?php
/**
 * Pointless Add Command
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

class AddCommand extends Command {
    public function __construct() {
        parent::__construct();
    }
    
    public function help() {
        IO::writeln('    add        - Add new article');
        IO::writeln('    add -s     - Add new Static Page');
    }

    public function run() {
        $config = Resource::get('config');
        
        $info = [
            'title' => IO::question("Enter Title:\n-> "),
            'url' => IO::question("Enter Custom Url:\n-> ")
        ];

        if(!$this->hasOptions('s')) {
            $info['tag'] = IO::question("Enter Tag:\n-> ");
            $info['category'] = IO::question("Enter Category:\n-> ");
        }

        if(NULL != $config['local_encoding']) {
            foreach($info as $key => $value) {
                $info[$key] = iconv($config['local_encoding'], 'utf-8', $value);
            }
        }

        if($this->hasOptions('s')) {
            $filename = $this->escape($info['url']);
            $filename = 'static_' . strtolower($filename) . '.md';
            $filepath = MARKDOWN . "/$filename";

            if(file_exists($filepath)) {
                IO::writeln("\nStatic Page $filename is exsist.");
                return;
            }

            $json = json_encode([
                'type' => 'static',
                'title' => $info['title'],
                'url' => $this->escape($info['url']),
                'message' => false,
            ], JSON_PRETTY_PRINT);

            $handle = fopen($filepath, 'w+');
            fwrite($handle, $json . "\n\n\n");
            fclose($handle);
            
            IO::writeln("\nStatic Page $filename was created.");
            system("{$config['editor']} $filepath < `tty` > `tty`");
        }
        else {
            $time = time();
            $filename = $this->escape($filename);
            $filename = sprintf("%s%s.md", date("Ymd_", $time), $info['url']);
            $filepath = MARKDOWN . "/$filename";

            if(file_exists($filepath)) {
                IO::writeln("\nArticle $filename is exsist.");
                return;
            }

            $json = json_encode([
                'type' => 'article',
                'title' => $info['title'],
                'url' => $this->escape($info['url']),
                'tag' => $info['tag'],
                'category' => $info['category'],
                'keywords' => null,
                'date' => date("Y-m-d", $time),
                'time' => date("H:i:s", $time),
                'message' => true,
                'publish' => false
            ], JSON_PRETTY_PRINT);

            $handle = fopen($filepath, 'w+');
            fwrite($handle, $json . "\n\n\n");
            fclose($handle);
            
            IO::writeln("\nArticle $filename is created.");
            system("{$config['editor']} $filepath < `tty` > `tty`");
        }
    }

    private function escape($filename) {
        $char = [
            "'", '"', '&', '$', '=',
            '!', '?', '/', '<', '>', 
            '(', ')', ':', ';', '@',
            '#', '%', '^', '*', ',',
            '.', '~', '`', '|', '\\'
        ];

        $filename = str_replace($char, '', $filename);
        return stripslashes($filename);
    }
}
