<?php
/**
 * Static Document Type
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

class StaticDoctype
{
    private $id;
    private $name;
    private $question;

    public function __construct()
    {
        $this->id = 'static';
        $this->name = 'Static Page';
        $this->question = [
            ['title', "Enter Title:\n-> "],
            ['url', "Enter Custom Url:\n-> "]
        ];
    }

    public function getID()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function save($post)
    {
        $filename = Utility::pathReplace($post['url']);
        $filename = strtolower($filename);
        $filename = "static_$filename.md";

        $savepath = MARKDOWN . "/$filename";

        if (file_exists($savepath)) {
            return false;
        }

        $json = json_encode([
            'type' => $this->id,
            'title' => $post['title'],
            'url' => Utility::pathReplace($post['url'], true),
            'message' => false,
            'publish' => false
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Create Markdown
        file_put_contents($savepath, "$json\n\n\n");

        return $savepath;
    }

    public function postHandle($post)
    {
        return [
            'title' => $post['title'],
            'url' => $post['url'],
            'content' => $post['content'],
            'message' => $post['message']
        ];
    }
}
