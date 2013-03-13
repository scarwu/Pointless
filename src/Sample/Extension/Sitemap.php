<?php
/**
 * Generate Sitemap
 */

class Sitemap {

	public function __construct() {}

	public function run() {
		NanoIO::writeln('Building Sitemap');

		$format = "\t<url>\n\t\t<loc>http://%s%s%s</loc>\n\t\t<lastmod>%s</lastmod>\n\t</url>\n";

		$sitemap = "<?xml version='1.0' encoding='UTF-8'?>\n";
		$sitemap .= "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n";

		foreach(Resource::get('sitemap') as $path) {
			$sitemap .= sprintf($format, BLOG_DNS, BLOG_PATH, $path, $this->getLastmod());
		}

		$sitemap .= "</urlset>";

		writeTo($sitemap, PUBLIC_FOLDER . 'sitemap.xml');
	}

	private function getLastmod() {
		$gmt = (date('d') - gmdate('d')) * 24 + (date('H') - gmdate('H'));
		$gmt_hour = (abs($gmt) < 10 ? '0' . abs($gmt) : abs($gmt));
		$gmt = ($gmt > 0 ? '+' . $gmt_hour : '-' . $gmt_hour) . ':00';

		return sprintf('%sT%s%s', date('Y-m-d'), date('H:i:s'), $gmt);
	}
}