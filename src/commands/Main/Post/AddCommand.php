<?php
/**
 * Pointless Post Add Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main\Post;

use Pointless\Library\Misc;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use NanoCLI\Command;
use NanoCLI\IO;

class AddCommand extends Command
{
    /**
     * Help
     */
    public function help()
    {
        IO::log('    post add    - Add new post');
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

        foreach ($doctype_list as $index => $doctype) {
            $class_name = 'Pointless\\Doctype\\' . ucfirst($doctype) . 'Doctype';
            $doctype_list[$index] = new $class_name;

            IO::log(sprintf('[ %3d] ', $index) . $doctype_list[$index]->getName());
        }

        $index = IO::ask("\nSelect Document Type:\n-> ", function ($answer) use ($doctype_list) {
            return is_numeric($answer)
                && $answer >= 0
                && $answer < count($doctype_list);
        });

        IO::writeln();

        // Ask Question
        $input = [];

        foreach ($doctype_list[$index]->getQuestion() as $question) {
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
        list($filename, $markdown_path) = $doctype_list[$index]->inputHandleAndSaveFile($input);

        if (null === $markdown_path) {
            IO::error($doctype_list[$index]->getName() . " {$filename} is exsist.");

            return false;
        }

        IO::notice($doctype_list[$index]->getName() . " {$filename} was created.");

        // Call CLI Editor to open file
        if (!Misc::editFile($markdown_path)) {
            IO::error("CLI editor is not found.");
        }
    }
}
