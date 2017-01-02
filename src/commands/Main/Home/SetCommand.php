<?php
/**
 * Pointless Set Home Command
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Command\Main\Home;

use Pointless\Library\Misc;
use NanoCLI\Command;
use NanoCLI\IO;

class SetCommand extends Command
{

    /**
     * @var string
     */
    private $path;

    /**
     * Help
     */
    public function help()
    {
        IO::log('    home set <path or not>');
        IO::log('                - Set another blog as default');
    }

    /**
     * Up
     */
    public function up()
    {
        $this->path = $this->getPath();

        if (!file_exists($this->path)) {
            IO::error("Path \"{$this->path}\" is't exists.");

            return false;
        }

        if (!file_exists("{$this->path}/.pointless")
            || !is_file("{$this->path}/.pointless")) {

            IO::error("Path \"{$this->path}\" is't the Pointless blog folder.");

            return false;
        }
    }

    /**
     * Run
     */
    public function run()
    {
        file_put_contents(HOME_ROOT . '/default', $this->path);

        IO::notice("Default blog is setting to path \"{$this->path}\".");
    }

    /**
     * Gte Path
     *
     * @return string
     */
    private function getPath()
    {
        $path = '';

        if ($this->getArguments(0)) {
            $path = $this->getArguments(0);
        }

        if (!preg_match('/^\/(.+)/', $path)) {
            $path = getcwd() . ('' !== $path ? "/{$path}" : $path);
        }

        return $path;
    }
}
