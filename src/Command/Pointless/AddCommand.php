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
        IO::writeln('    add        - Add new page');
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

        // Load Page Type
        $type = [];
        $handle = opendir(ROOT . '/Type');
        while ($filename = readdir($handle)) {
            if (!preg_match('/.php$/', $filename)) {
                continue;
            }

            $filename = preg_replace('/.php$/', '', $filename);

            require ROOT . "/Type/$filename.php";
            $type[] = new $filename;
        }
        closedir($handle);

        foreach ($type as $index => $class) {
            IO::writeln(sprintf("[%3d] ", $index) . $class->getName());
        }

        $number = IO::question("\nEnter Number:\n-> ", null, function ($answer) use ($type) {
            return is_numeric($answer) && $answer >= 0 && $answer < count($type);
        });

        // Ask question
        $info = [];
        foreach ($type[$number]->getQuestion() as $question) {
            $info[$question[0]] = IO::question($question[1]);
        }

        // Convert Encoding
        $encoding = Resource::get('config')['encoding'];
        if (null !== $encoding) {
            foreach ($info as $key => $value) {
                $info[$key] = iconv($encoding, 'utf-8', $value);
            }
        }

        $savepath = $type[$number]->save($info);
        if (null === $savepath) {
            IO::writeln($type[$number]->getName() . " $filename is exsist.");
            return false;
        }

        IO::writeln($type[$number]->getName() . " $filename was created.");

        system("$editor $savepath < `tty` > `tty`");
    }
}
