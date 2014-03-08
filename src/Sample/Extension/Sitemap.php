<?php
/**
 * Sitemap Generator Extension
 *
 * @package     Pointless
 * @author      ScarWu
 * @copyright   Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link        http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class Sitemap
{
    /**
     * Run Generator
     */
    public function run()
    {
        IO::writeln('Building Sitemap');

        $blog = Resource::get('config')['blog'];
        $blog['url'] = $blog['dn'] . $blog['base'];

        $format = "\t<url>\n\t\t<loc>http://%s%s</loc>\n\t\t<lastmod>%s</lastmod>\n\t</url>\n";

        $sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $sitemap .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        foreach ((array) Resource::get('sitemap') as $path) {
            $sitemap .= sprintf($format, $blog['url'], $path, date(DATE_ATOM));
        }

        $sitemap .= sprintf($format, $blog['url'], '', date(DATE_ATOM));
        $sitemap .= "</urlset>";

        writeTo($sitemap, TEMP . '/sitemap.xml');
    }
}
