<?php
/**
 * Document Type
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2017, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

namespace Pointless\Extend;

abstract class Doctype
{
    protected $type;
    protected $name;
    protected $question;

    protected function __construct()
    {
        $this->type = null;
        $this->name = null;
        $this->question = [];
    }

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

        $savepath = BLOG_MARKDOWN . "/{$filename}";

        if (file_exists($savepath)) {
            return [$filename, null];
        }

        // Convert to JSON
        $json = json_encode($hedaer, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Create Markdown
        file_put_contents($savepath, "<!--{$json}-->\n\n# {$title}\n\n");

        $this->savepath = $savepath;

        return [$filename, $savepath];
    }
}
