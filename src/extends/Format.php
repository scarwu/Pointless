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

    /**
     * Get Type
     *
     * @return string
     */
    final public function getType()
    {
        return $this->type;
    }

    /**
     * Get Name
     *
     * @return string
     */
    final public function getName()
    {
        return $this->name;
    }

    /**
     * Get Question List
     *
     * @return array
     */
    final public function getQuestionList()
    {
        return $this->questionList;
    }

    /**
     * Save To File
     *
     * @param array $info
     *
     * @return array
     */
    final public function saveToFile($info)
    {
        $filename = $info['filename'];
        $title = $info['title'];
        $hedaer = $info['header'];

        $filepath = BLOG_POST . "/{$filename}.md";

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

    /**
     * Convert Input
     *
     * @param array $input
     *
     * @return array
     */
    public function convertInput($input)
    {
        return [];
    }

    /**
     * Convert Post
     *
     * @param array $post
     *
     * @return array
     */
    public function convertPost($post)
    {
        return [];
    }
}
