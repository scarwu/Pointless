<?php
/**
 * Pointless Post Add Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2016, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main\Post;

use Pointless\Library\Misc,
    Pointless\Library\Utility,
    Pointless\Library\Resource,
    NanoCLI\Command,
    NanoCLI\IO;

class AddCommand extends Command
{
    private $editor;

    public function help()
    {
        IO::log('    post add    - Add new post');
    }

    public function up()
    {
        if (!Misc::checkDefaultBlog()) {
            return false;
        }

        Misc::initBlog();

        // Check Editor
        $this->editor = Resource::get('config')['editor'];

        if (!Utility::commandExists($this->editor)) {
            IO::error("System command \"{$this->editor}\" is not found.");

            return false;
        }
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

            $type[] = new $filename;
        }

        closedir($handle);

        // Select Doctype
        foreach ($type as $index => $class) {
            IO::log(sprintf('[ %3d] ', $index) . $class->getName());
        }

        $select = IO::ask("\nSelect Document Type:\n-> ", function ($answer) use ($type) {
            return is_numeric($answer)
                && $answer >= 0
                && $answer < count($type);
        });

        IO::writeln();

        // Ask Question
        $input = [];

        foreach ($type[$select]->getQuestion() as $question) {
            $input[$question[0]] = IO::ask($question[1]);
        }

        // Convert Encoding
        $encoding = Resource::get('config')['encoding'];

        if (null !== $encoding) {
            foreach ($input as $key => $value) {
                $input[$key] = iconv($encoding, 'utf-8', $value);
            }
        }

        // Save File
        list($filename, $savepath) = $type[$select]->inputHandleAndSaveFile($input);

        if (null === $savepath) {
            IO::error($type[$select]->getName() . " {$filename} is exsist.");

            return false;
        }

        IO::notice($type[$select]->getName() . " {$filename} was created.");

        system("{$this->editor} {$savepath} < `tty` > `tty`");
    }
}
