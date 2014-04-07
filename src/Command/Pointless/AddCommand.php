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
    public function __construct()
    {
        parent::__construct();
    }

    public function help()
    {
        IO::writeln('    add        - Add new post');
    }

    public function run()
    {
        if (!checkDefaultBlog()) {
            return false;
        }

        initBlog();

        // Check System Command
        $editor = Resource::get('config')['editor'];
        if (!Utility::commandExists($editor)) {
            IO::writeln("System command \"$editor\" is not found.", 'red');
            return false;
        }

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
            IO::writeln(sprintf("[ %3d] ", $index) . $class->getName());
        }
        $select = IO::question("\nSelect Document Type:\n-> ", null, function ($answer) use ($type) {
            return is_numeric($answer) && $answer >= 0 && $answer < count($type);
        });

        // Ask Question
        $header = [];
        foreach ($type[$select]->getQuestion() as $question) {
            $header[$question[0]] = IO::question($question[1]);
        }

        // Convert Encoding
        $encoding = Resource::get('config')['encoding'];
        if (null !== $encoding) {
            foreach ($header as $key => $value) {
                $header[$key] = iconv($encoding, 'utf-8', $value);
            }
        }

        // Save Header
        $type[$select]->headerHandleAndSave($header);
        $savepath = $type[$select]->getSavepath();
        $filename = $type[$select]->getFilename();
        if (null === $savepath) {
            IO::writeln($type[$select]->getName() . " $filename is exsist.");
            return false;
        }

        IO::writeln($type[$select]->getName() . " $filename was created.");
        system("$editor $savepath < `tty` > `tty`");
    }
}
