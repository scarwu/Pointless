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

use Pointless\Library\BlogCore;
use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Pointless\Extend\Task;

class AddTask extends Task
{
    /**
     * Help Info
     */
    public function helpInfo()
    {
        $this->io->log('    post add                - Add new post');
    }

    /**
     * Lifecycle Funtions
     */
    public function up()
    {
        // Init Blog
        if (false === BlogCore::init()) {
            $this->io->error('Please init blog first.');

            return false;
        }
    }

    public function run()
    {
        $formatList = [];

        foreach (Resource::get('system:constant')['formats'] as $index => $name) {
            $namespace = 'Pointless\\Format\\' . ucfirst($name);

            $formatList[$index] = new $namespace();

            $this->io->log(sprintf('[ %3d] ', $index) . $formatList[$index]->getName());
        }

        $index = $this->io->ask("\nSelect Document Format:\n-> ", function ($answer) use ($formatList) {
            return is_numeric($answer)
                && $answer >= 0
                && $answer < count($formatList);
        });

        $this->io->writeln();

        // Ask Question
        $input = [];

        foreach ($formatList[$index]->getQuestionList() as $question) {
            $input[$question['name']] = $this->io->ask($question['statement']);
        }

        // Convert Encoding
        $encoding = Resource::get('system:config')['encoding'];

        if (null !== $encoding) {
            foreach ($input as $key => $value) {
                $input[$key] = iconv($encoding, 'utf-8', $value);
            }
        }

        // Save File
        list($filename, $filepath) = $formatList[$index]->convertInput($input);

        if (null === $filepath) {
            $this->io->error($formatList[$index]->getName() . " {$filename} is exsist.");

            return false;
        }

        $this->io->notice($formatList[$index]->getName() . " {$filename} was created.");

        // Call CLI Editor to open file
        BlogCore::editFile($filepath);
    }
}
