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

use Utility;
use Resource;

class AddCommand extends Command
{
    private $editor;

    public function help()
    {
        IO::log('    add        - Add new post');
    }

    public function up()
    {
        if (!checkDefaultBlog()) {
            return false;
        }

        initBlog();

        $this->editor = Resource::get('config')['editor'];
        if (!Utility::commandExists($this->editor)) {
            IO::error("System command \"$this->editor\" is not found.");

            return false;
        }
    }

    public function run()
    {
        // Load Doctype
        $type = [];
        $handle = opendir(ROOT . '/Doctype');
        while ($filename = readdir($handle)) {
            if (!preg_match('/.php$/', $filename)) {
                continue;
            }

            $filename = preg_replace('/.php$/', '', $filename);

            require ROOT . "/Doctype/$filename.php";
            $type[] = new $filename;
        }
        closedir($handle);

        // Select Doctype
        foreach ($type as $index => $class) {
            IO::log(sprintf("[ %3d] ", $index) . $class->getName());
        }
        $select = IO::ask("\nSelect Document Type:\n-> ", function ($answer) use ($type) {
            return is_numeric($answer) && $answer >= 0 && $answer < count($type);
        });

        // Ask Question
        $header = [];
        foreach ($type[$select]->getQuestion() as $question) {
            $header[$question[0]] = IO::ask($question[1]);
        }

        // Convert Encoding
        $encoding = Resource::get('config')['encoding'];
        if (null !== $encoding) {
            foreach ($header as $key => $value) {
                $header[$key] = iconv($encoding, 'utf-8', $value);
            }
        }

        // Save Header
        list($filename, $savepath) = $type[$select]->headerHandleAndSave($header);
        if (null === $savepath) {
            IO::error($type[$select]->getName() . " $filename is exsist.");

            return false;
        }

        IO::notice($type[$select]->getName() . " $filename was created.");
        system("$this->editor $savepath < `tty` > `tty`");
    }
}
