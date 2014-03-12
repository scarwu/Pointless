<?php
/**
 * Atom Generator Extension
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class Atom
{
    /**
     * Run Generator
     */
    public function run()
    {
        IO::writeln('Building Atom');

        $quantity = Resource::get('config')['feed_quantity'];
        $blog = Resource::get('config')['blog'];
        $blog['url'] = $blog['dn'] . $blog['base'];

        $count = 0;

        $atom = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $atom .= "<feed xmlns=\"http://www.w3.org/2005/Atom\">\n";

        $atom .= "\t<title><![CDATA[{$blog['name']}]]></title>\n";
        $atom .= "\t<subtitle>{$blog['slogan']}</subtitle>\n";
        $atom .= "\t<link href=\"http://{$blog['url']}atom.xml\" rel=\"self\" />\n";
        $atom .= "\t<link href=\"http://{$blog['url']}\" />\n";
        $atom .= "\t<id>urn:uuid:" . $this->uuid("{$blog['url']}atom.xml") . "</id>\n";
        $atom .= "\t<updated>" . date(DATE_ATOM) . "</updated>\n";

        if (null !== $blog['author'] || null !== $blog['email']) {
            $atom .= "\t<author>\n";

            if (null !== $blog['author']) {
                $atom .= "\t\t<name><![CDATA[{$blog['author']}]]></name>\n";
            }

            if (null !== $blog['email']) {
                $atom .= "\t\t<email>{$blog['email']}</email>\n";
            }

            $atom .= "\t\t<uri>http://{$blog['url']}</uri>\n";
            $atom .= "\t</author>\n";
        }

        foreach ((array) Resource::get('article') as $article) {
            $title = $article['title'];
            $url = "{$blog['url']}article/{$article['url']}";
            $uuid = $this->uuid($url);
            $date = date(DATE_ATOM, $article['timestamp']);
            $summary = $article['content'];

            $atom .= "\t<entry>\n";
            $atom .= "\t\t<title type=\"html\"><![CDATA[{$title}]]></title>\n";
            $atom .= "\t\t<link href=\"http://{$url}\" />\n";
            $atom .= "\t\t<id>urn:uuid:{$uuid}</id>\n";
            $atom .= "\t\t<updated>{$date}</updated>\n";
            $atom .= "\t\t<summary type=\"html\"><![CDATA[{$summary}]]></summary>\n";
            $atom .= "\t</entry>\n";

            if (++$count >= $quantity) {
                break;
            }
        }

        $atom .= "</feed>";

        writeTo($atom, TEMP . '/atom.xml');
    }

    /**
     * UUID Generator
     *
     * @param string
     * @return string
     */
    private function uuid($input)
    {
        $chars = md5($input);

        $uuid  = substr($chars, 0, 8) . '-';
        $uuid .= substr($chars, 8, 4) . '-';
        $uuid .= substr($chars, 12, 4) . '-';
        $uuid .= substr($chars, 16, 4) . '-';
        $uuid .= substr($chars, 20, 12);

        return $uuid;
    }
}
