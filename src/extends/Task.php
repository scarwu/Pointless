<?php
/**
 * Task
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Extend;

use Pointless\Library\Utility;
use Pointless\Library\Resource;
use Oni\CLI\Task as CLITask;

abstract class Task extends CLITask
{
    /**
     * Show Banner
     */
    protected function showBanner()
    {
        $banner = <<<EOF
                                           __                          _______
      ______  ______  __  ______  ______  / /\______  _____  _____    / _____/\
     / __  /\/ __  /\/ /\/ __  /\/_  __/\/ / /  ___/\/  __/\/  __/\  / /_____\/
    / /_/ / / /_/ / / / / /\/ / /\/ /\_\/ / /  ___/\/\  \_\/\  \_\/ /______ \
   / ____/ /_____/ /_/ /_/ /_/ / /_/ / /_/ /_____/\/____/\/____/\/ _\_____/ /\
  /_/\___\/\_____\/\_\/\_\/\_\/  \_\/  \_\/\_____\/\____\/\____\/ /________/ /
  \_\/                                                            \_________/

EOF;

        $this->io->notice($banner);
    }

    /**
     * Edit File
     *
     * @param string $path
     *
     * @return bool
     */
    protected function editFile(string $path): bool
    {
        if (false === is_string($path)) {
            return false;
        }

        $editor = Resource::get('config:blog')['editor'];

        if (false === Utility::commandExists($editor)) {
            $this->io->error("System command \"{$editor}\" is not found.");

            return false;
        }

        system("{$editor} {$path} < `tty` > `tty`");

        return true;
    }
}
