<?php
/**
 * Generate Sitemap
 */

use NanoCLI\IO;

class Sitemap {

	public function __construct() {}

	public function run() {
		IO::writeln('Building Sitemap');

		$format = "\t<url>\n\t\t<loc>http://%s%s%s</loc>\n\t\t<lastmod>%s</lastmod>\n\t</url>\n";

		$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$sitemap .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

		foreach(Resource::get('sitemap') as $path) {
			$sitemap .= sprintf($format, BLOG_DNS, BLOG_PATH, $path, date(DATE_ATOM));
		}

		$sitemap .= "</urlset>";

		writeTo($sitemap, PUBLIC_FOLDER . 'sitemap.xml');
	}
	
}