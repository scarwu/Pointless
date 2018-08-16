<?php
/**
 * Sitemap Generator Extension
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (https://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

namespace Pointless\Extension;

use Pointless\Extend\Extension;

class Sitemap extends Extension
{
    public function __construct()
    {
        $this->path = 'sitemap.xml';
    }

    /**
     * Render
     */
    public function render($data)
    {
        $scheme = $data['systemConfig']['blog']['withSSL'] ? 'https' : 'http';
        $domainName = $data['systemConfig']['blog']['domainName'];
        $baseUrl = $data['systemConfig']['blog']['baseUrl'];

        $format = "\t<url>\n\t\t<loc>{$scheme}://%s%s</loc>\n\t\t<lastmod>%s</lastmod>\n\t</url>\n";

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        foreach ($data['postPathList'] as $path) {
            $xml .= sprintf($format, "{$domainName}{$baseUrl}", $path, date(DATE_ATOM));
        }

        $xml .= sprintf($format, "{$domainName}{$baseUrl}", '', date(DATE_ATOM));
        $xml .= "</urlset>";

        return $xml;
    }
}
