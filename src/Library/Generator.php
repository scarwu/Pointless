<?php

class Generator {
	private $script;
	private $slider;
	
	public function __construct() {
		$this->script = array();
	}
	
	public function run() {

		// Load Theme Custom Script
		if(file_exists(THEME . 'Script')) {
			$handle = opendir(THEME . 'Script');
			while($filename = readdir($handle))
				if('.' != $filename && '..' != $filename) {
					require THEME . 'Script' . $filename;

					$class_name = preg_replace('/.php$/', '', $filename);
					$this->script[$class_name] = new $class_name;
				}
			closedir($handle);
		}

		// Load Default Script
		$handle = opendir(SCRIPT);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				$class_name = preg_replace('/.php$/', '', $filename);

				if(!isset($this->script[$class_name])) {
					require SCRIPT . $filename;
					$this->script[$class_name] = new $class_name;
				}
			}
		closedir($handle);
		
		$this->genSlider();
		$this->genContainer();
		$this->genSitemap();
	}

	/**
	 * Generate Container
	 */
	private function genContainer() {
		foreach((array)$this->script as $class)
			$class->gen($this->slider);
	}

	/**
	 * Generate Slider
	 */
	private function genSlider() {
		$list = array();
		$handle = opendir(THEME_SLIDER);
		while($file = readdir($handle))
			if('.' != $file && '..' != $file)
				$list[] = $file;
		closedir($handle);
		
		sort($list);

		$result = '';
		foreach((array)$list as $filename)
			$result .= bindData(
				$this->script[preg_replace(array('/^\d+_/', '/.php$/'), '', $filename)]->getList(),
				THEME_SLIDER . $filename
			);
		
		$this->slider = $result;
	}

	/**
	 * Generate Sitemap
	 */
	private function genSitemap() {
		NanoIO::writeln('Generating Sitemap ...', 'yellow');

		$GMT = (date('d') - gmdate('d')) * 24 + (date('H') - gmdate('H'));
		$GMT_hour = (abs($GMT) < 10 ? '0' . abs($GMT) : abs($GMT));
		$GMT = ($GMT > 0 ? '+' . $GMT_hour : '-' . $GMT_hour) . ':00';
		$date = sprintf('%sT%s%s', date('Y-m-d'), date('H:i:s'), $GMT);

		$format = <<<EOF
	<url>
		<loc>http://%s%s%s</loc>
		<lastmod>%s</lastmod>
	</url>
EOF;

		$sitemap = "<?xml version='1.0' encoding='UTF-8'?>\n";
		$sitemap .= "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n";

		foreach (Resource::get('sitemap') as $path) {
			$sitemap .= sprintf($format, BLOG_DNS, BLOG_PATH, $path, $date);
		}

		$sitemap .= "</urlset>";

		writeTo($sitemap, PUBLIC_FOLDER . 'sitemap.xml');
	}
}
