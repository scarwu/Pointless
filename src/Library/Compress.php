<?php

class Compress {
	private $_css_list;
	private $_js_list;
	
	public function __construct() {
		$this->_css_list = array();
		$this->_js_list = array();
	}

	public function css($src, $dest) {
		$handle = opendir($src);
		while($file = readdir($handle))
			if('.' != $file && '..' != $file)
				$this->_css_list[] = $file;

		closedir($handle);
		
		sort($this->_css_list);
		
		if(!file_exists($dest))
			mkdir($dest, 0755, TRUE);
		
		$css_package = fopen(rtrim($dest, '/') . '/main.css', 'w+');
		foreach((array)$this->_css_list as $filename) {
			$css = file_get_contents($src . $filename);
			$css = $this->cssCompressor($css);
			fwrite($css_package, $css);
		}
		fclose($css_package);
	}
	
	// Css Compressor
	private function cssCompressor($css) {
		$css = preg_replace('/(\f|\n|\r|\t|\v)/', '', $css);
		$css = preg_replace('/\/\*.+?\*\//', '', $css);
		$css = preg_replace('/[ ]+/', ' ', $css);
		$css = str_replace(
			array(' ,', ', ', ': ', ' :', ' {', '{ ', ' }', '} ', ' ;', '; '),
			array(',', ',', ':', ':', '{', '{', '}', '}', ';', ';'),
			$css
		);
		return $css;
	}
	
	public function js($src, $dest) {
		$handle = opendir($src);
		while($file = readdir($handle))
			if('.' != $file && '..' != $file)
				$this->_js_list[] = $file;
		closedir($handle);
		
		sort($this->_js_list);
		
		if(!file_exists($dest))
			mkdir($dest, 0755, TRUE);
		
		$js_package = fopen(rtrim($dest, '/') . '/main.js', 'w+');
		foreach((array)$this->_js_list as $filename) {
			$handle = fopen($src . $filename, 'r');
			while($data = fread($handle, 1024))
				fwrite($js_package, $data, 1024);
			fwrite($js_package, "\n");
			fclose($handle);
		}
		fclose($js_package);
	}
}
