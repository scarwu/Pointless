<?php
/**
 * Document Format
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Extend;

abstract class Format
{
    /**
     * @var string $type
     */
    protected $type = null;

    /**
     * @var string $name
     */
    protected $name = null;

    /**
     * @var mixed $question
     */
    protected $question = [];

    abstract public function inputHandleAndSaveFile($input);

    abstract public function postHandleAndGetResult($post);

    final public function getType()
    {
        return $this->type;
    }

    final public function getName()
    {
        return $this->name;
    }

    final public function getQuestion()
    {
        return $this->question;
    }

    final public function save($info)
    {
        $filename = $info['filename'];
        $title = $info['title'];
        $hedaer = $info['header'];

        $filepath = BLOG_POST . "/{$filename}";

        if (file_exists($filepath)) {
            return [$filename, null];
        }

        // Convert to JSON
        $json = json_encode($hedaer, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Create Post
        file_put_contents($filepath, "<!--{$json}-->\n\n# {$title}\n\n");

        return [
            $filename,
            $filepath
        ];
    }
}
