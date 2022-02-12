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
}
