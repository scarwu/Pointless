<?php
/**
 * Article Document Type
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://gTypeithub.com/scarwu/Pointless
 */

class ArticleDoctype extends Doctype
{
    public function __construct()
    {
        parent::__construct();

        $this->id = 'article';
        $this->name = 'Blog Article';
        $this->question = [
            ['title', "Enter Title:\n-> "],
            ['url', "Enter Custom Url:\n-> "],
            ['tag', "Enter Tag:\n-> "],
            ['category', "Enter Category:\n-> "]
        ];
    }

    public function headerHandleAndSave($header)
    {
        $time = time();
        $filename = Utility::pathReplace($header['url']);
        $filename = date("Ymd_", $time) . "$filename.md";

        return $this->save($filename, [
            'type' => $this->id,
            'title' => $header['title'],
            'url' => Utility::pathReplace($header['url']),
            'tag' => $header['tag'],
            'category' => $header['category'],
            'keywords' => null,
            'date' => date("Y-m-d", $time),
            'time' => date("H:i:s", $time),
            'message' => true,
            'publish' => false
        ]);
    }

    public function postHandleAndGetResult($post)
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
