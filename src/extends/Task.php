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

use Pointless\Library\BlogCore;
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

        $this->io->writeln($banner, 'green');
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

        $editor = Resource::get('blog:config')['editor'];

        if (false === Utility::commandExists($editor)) {
            $this->io->error("System command \"{$editor}\" is not found.");

            return false;
        }

        system("{$editor} {$path} < `tty` > `tty`");

        return true;
    }

    /**
     * Select Format Item
     */
    protected function selectFormatItem(): object
    {
        $formatList = [];
        $options = [];

        foreach (Resource::get('system:constant')['formats'] as $name) {
            $namespace = 'Pointless\\Format\\' . ucfirst($name);
            $formatItem = new $namespace();

            $formatList[] = $formatItem;
            $options[] = $formatItem->getName();
        }

        $index = $this->io->menuSelector('Select Document Format:', $options);

        $this->io->writeln();

        return $formatList[$index];
    }

    /**
     * Select Post Data
     */
    protected function selectPostData($type): ?array
    {
        $postList = BlogCore::getPostList($type, true);
        $postList = array_reverse($postList);

        if (0 === count($postList)) {
            return null;
        }

        $options = [];

        foreach ($postList as $index => $post) {
            $title = (false === $post['params']['isPublic'])
                ? "🔒{$post['title']}" : $post['title'];

            $options[] = (true === isset($post['params']['date']))
                ? "[{$post['params']['date']}] {$title}" : $title;
        }

        $index = $this->io->menuSelector('Select Post:', $options, 12);

        $this->io->writeln();

        return $postList[array_keys($postList)[$index]];
    }

    /**
     * Select Theme Data
     */
    protected function selectThemeData(): ?array
    {
        $themeList = BlogCore::getThemeList();

        if (0 === count($themeList)) {
            return null;
        }

        $options = [];

        foreach ($themeList as $index => $theme) {
            $options[] = $theme['title'];
        }

        $index = $this->io->menuSelector('Select Theme:', $options, 12);

        $this->io->writeln();

        return $themeList[array_keys($themeList)[$index]];
    }
}
