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

use Pointless\Library\Utility;

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
        $type = $info['type'];
        $filename = $info['filename'];
        $title = $info['title'];
        $header = $info['header'];

        $filepath = BLOG_POST . "/{$type}/{$filename}.md";

        if (file_exists($filepath)) {
            return [$filename, null];
        }

        Utility::saveMarkdownFile($filepath, $header, "# {$title}");

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
