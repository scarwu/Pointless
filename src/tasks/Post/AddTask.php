<?php
/**
 * Post Add Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Task\Post;

use Pointless\Library\Misc;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Oni\CLI\Task;

class AddTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('    post add        - Add new post');
    }

    /**
     * Up
     */
    public function up()
    {
        // Init Blog
        if (false === Misc::initBlog()) {
            return false;
        }
    }

    /**
     * Run
     */
    public function run()
    {
        $format_list = [];

        foreach (Resource::get('system:constant')['formats'] as $index => $sub_class_name) {
            $class_name = 'Pointless\\Format\\' . ucfirst($sub_class_name);
            $format_list[$index] = new $class_name;

            $this->io->log(sprintf('[ %3d] ', $index) . $format_list[$index]->getName());
        }

        $index = $this->io->ask("\nSelect Document Format:\n-> ", function ($answer) use ($format_list) {
            return is_numeric($answer)
                && $answer >= 0
                && $answer < count($format_list);
        });

        $this->io->writeln();

        // Ask Question
        $input = [];

        foreach ($format_list[$index]->getQuestion() as $question) {
            $input[$question[0]] = $this->io->ask($question[1]);
        }

        // Convert Encoding
        $encoding = Resource::get('system:config')['encoding'];

        if (null !== $encoding) {
            foreach ($input as $key => $value) {
                $input[$key] = iconv($encoding, 'utf-8', $value);
            }
        }

        // Save File
        list($filename, $filepath) = $format_list[$index]->inputHandleAndSaveFile($input);

        if (null === $filepath) {
            $this->io->error($format_list[$index]->getName() . " {$filename} is exsist.");

            return false;
        }

        $this->io->notice($format_list[$index]->getName() . " {$filename} was created.");

        // Call CLI Editor to open file
        Misc::editFile($filepath);
    }
}
