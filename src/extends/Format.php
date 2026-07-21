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
     * @var string|null
     */
    protected ?string $type = null;

    /**
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * @var array
     */
    protected array $questionList = [];

    /**
     * Get Type
     *
     * @return string|null
     */
    final public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Get Name
     *
     * @return string|null
     */
    final public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get Question List
     *
     * @return array
     */
    final public function getQuestionList(): array
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
    final public function saveToFile(array $info): array
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
    public function convertInput(array $input): array
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
    public function convertPost(array $post): array
    {
        return [];
    }
}
