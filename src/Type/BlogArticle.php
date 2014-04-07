<?php
/**
 * Article Document Type
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

class BlogArticle
{
    private $id;
    private $name;
    private $question;

    public function __construct()
    {
        $this->id = 'article';
        $this->name = 'Blog Article';
        $this->question = [
            ['title', "Enter Title:\n-> "],
            ['url', "Enter Custom Url:\n-> "],
            ['tag', "Enter Tag:\n-> "],
            ['category', "Enter Category:\n-> "]
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

    public function save($info)
    {
        $time = time();
        $filename = Utility::pathReplace($info['url']);
        $filename = date("Ymd_", $time) . "$filename.md";

        $savepath = MARKDOWN . "/$filename";

        if (file_exists($savepath)) {
            return false;
        }

        $json = json_encode([
            'type' => $this->id,
            'title' => $info['title'],
            'url' => Utility::pathReplace($info['url']),
            'tag' => $info['tag'],
            'category' => $info['category'],
            'keywords' => null,
            'date' => date("Y-m-d", $time),
            'time' => date("H:i:s", $time),
            'message' => true,
            'publish' => false
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Create Markdown
        file_put_contents($savepath, "$json\n\n\n");

        return $savepath;
    }
}
