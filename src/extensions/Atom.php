<?php
/**
 * Atom Generator Extension
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Extension;

use Pointless\Extend\Extension;

class Atom extends Extension
{
    public function __construct()
    {
        $this->path = 'atom.xml';
    }

    /**
     * Render
     */
    public function render($data)
    {
        $scheme = $data['blog']['config']['withSSL'] ? 'https' : 'http';
        $domainName = $data['blog']['config']['domainName'];
        $baseUrl = $data['blog']['config']['baseUrl'];

        $name = $data['blog']['config']['name'];
        $slogan = $data['blog']['config']['slogan'];
        $author = $data['blog']['config']['author'];
        $email = $data['blog']['config']['email'];

        $quantity = $data['blog']['config']['extension']['atom']['quantity'];
        $count = 0;

        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $xml .= "<feed xmlns=\"http://www.w3.org/2005/Atom\">\n";

        $xml .= "\t<title><![CDATA[{$name}]]></title>\n";
        $xml .= "\t<subtitle><![CDATA[{$slogan}]]></subtitle>\n";
        $xml .= "\t<link href=\"{$scheme}://{$domainName}{$baseUrl}atom.xml\" rel=\"self\" />\n";
        $xml .= "\t<link href=\"{$scheme}://{$domainName}{$baseUrl}\" />\n";
        $xml .= "\t<id>urn:uuid:" . $this->uuid("{$domainName}{$baseUrl}atom.xml") . "</id>\n";
        $xml .= "\t<updated>" . date(DATE_ATOM) . "</updated>\n";

        if (null !== $author || null !== $email) {
            $xml .= "\t<author>\n";

            if (null !== $author) {
                $xml .= "\t\t<name><![CDATA[{$author}]]></name>\n";
            }

            if (null !== $email) {
                $xml .= "\t\t<email>{$email}</email>\n";
            }

            $xml .= "\t\t<uri>{$scheme}://{$domainName}{$baseUrl}</uri>\n";
            $xml .= "\t</author>\n";
        }

        foreach ($data['postBundle']['article'] as $post) {
            $title = $post['title'];
            $url = "{$domainName}{$baseUrl}article/{$post['url']}";
            $uuid = $this->uuid($url);
            $date = date(DATE_ATOM, $post['modifyTime']);
            $summary = $post['content'];

            $xml .= "\t<entry>\n";
            $xml .= "\t\t<title type=\"html\"><![CDATA[{$title}]]></title>\n";
            $xml .= "\t\t<link href=\"{$scheme}://{$url}\" />\n";
            $xml .= "\t\t<id>urn:uuid:{$uuid}</id>\n";
            $xml .= "\t\t<updated>{$date}</updated>\n";
            $xml .= "\t\t<summary type=\"html\"><![CDATA[{$summary}]]></summary>\n";
            $xml .= "\t</entry>\n";

            if (++$count >= $quantity) {
                break;
            }
        }

        $xml .= "</feed>";

        return $xml;
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
