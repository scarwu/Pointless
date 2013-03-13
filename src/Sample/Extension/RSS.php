<?php
/**
 * Generate RSS
 */

class RSS {

	public function __construct() {}

	public function run() {
		NanoIO::writeln('Generating RSS ...', 'yellow');

		$rss = '';

		writeTo($rss, PUBLIC_FOLDER . 'rss.xml');
	}
}