<?php
/**
 * Article Document Type
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://gTypeithub.com/scarwu/Pointless
 */

class ArticleDoctype
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

    public function save($post)
    {
        $time = time();
        $filename = Utility::pathReplace($post['url']);
        $filename = date("Ymd_", $time) . "$filename.md";

        $savepath = MARKDOWN . "/$filename";

        if (file_exists($savepath)) {
            return false;
        }

        $json = json_encode([
            'type' => $this->id,
            'title' => $post['title'],
            'url' => Utility::pathReplace($post['url']),
            'tag' => $post['tag'],
            'category' => $post['category'],
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

    public function postHandle($post)
    {
        $format = Resource::get('config')['article_url'];
        $format = trim($format, '/');

        list($year, $month, $day) = explode('-', $post['date']);
        list($hour, $minute, $second) = explode(':', $post['time']);
        $timestamp = strtotime("$day-$month-$year {$post['time']}");

        // Generate custom url
        $url = str_replace([
            ':year', ':month', ':day',
            ':hour', ':minute', ':second', ':timestamp',
            ':title', ':url'
        ], [
            $year, $month, $day,
            $hour, $minute, $second, $timestamp,
            $post['title'], $post['url']
        ], $format);

        $post['tag'] = explode('|', $post['tag']);
        sort($post['tag']);

        return [
            'title' => $post['title'],
            'url' => $url,
            'content' => $post['content'],
            'date' => $post['date'],
            'time' => $post['time'],
            'category' => $post['category'],
            'keywords' => $post['keywords'],
            'tag' => $post['tag'],
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour' => $hour,
            'minute' => $minute,
            'second' => $second,
            'timestamp' => $timestamp,
            'message' => $post['message']
        ];
    }
}
