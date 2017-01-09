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
        $formatList = [];

        foreach (Resource::get('attr:constant')['formats'] as $index => $subClassName) {
            $className = 'Pointless\\Format\\' . ucfirst($subClassName);
            $formatList[$index] = new $className;

            IO::log(sprintf('[ %3d] ', $index) . $formatList[$index]->getName());
        }

        $index = IO::ask("\nSelect Document Format:\n-> ", function ($answer) use ($formatList) {
            return is_numeric($answer)
                && $answer >= 0
                && $answer < count($formatList);
        });

        IO::writeln();

        // Ask Question
        $input = [];

        foreach ($formatList[$index]->getQuestion() as $question) {
            $input[$question[0]] = IO::ask($question[1]);
        }

        // Convert Encoding
        $encoding = Resource::get('attr:config')['encoding'];

        if (null !== $encoding) {
            foreach ($input as $key => $value) {
                $input[$key] = iconv($encoding, 'utf-8', $value);
            }
        }

        // Save File
        list($filename, $filepath) = $formatList[$index]->inputHandleAndSaveFile($input);

        if (null === $filepath) {
            IO::error($formatList[$index]->getName() . " {$filename} is exsist.");

            return false;
        }

        IO::notice($formatList[$index]->getName() . " {$filename} was created.");

        // Call CLI Editor to open file
        Misc::editFile($filepath);
    }
}
