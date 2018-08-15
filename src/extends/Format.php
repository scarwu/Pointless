<?php
/**
 * Document Format
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Extend;

abstract class Format
{
    /**
     * @var string
     */
    protected $type = null;

    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var array
     */
    protected $questionList = [];

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

    final public function getQuestionList()
    {
        return $this->questionList;
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
