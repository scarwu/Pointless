<?php
/**
 * Document Type
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://gTypeithub.com/scarwu/Pointless
 */

abstract class Doctype
{
    protected $id;
    protected $name;
    protected $question;

    protected function __construct()
    {
        $this->id = null;
        $this->name = null;
        $this->question = [];
    }

    abstract public function headerHandleAndSave($header);

    abstract public function postHandleAndGetResult($post);

    final public function getID()
    {
        return $this->id;
    }

    final public function getName()
    {
        return $this->name;
    }

    final public function getQuestion()
    {
        return $this->question;
    }

    final public function save($filename, $post)
    {
        $savepath = MARKDOWN . "/$filename";

        if (file_exists($savepath)) {
            return [$filename, null];
        }

        // Convert to JSON
        $option = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE;
        $json = json_encode($post, $option);

        // Create Markdown
        file_put_contents($savepath, "$json\n\n\n");

        $this->savepath = $savepath;

        return [$filename, $savepath];
    }
}
