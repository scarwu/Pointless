<?php

class Compress {
	private $css_list;
	private $js_list;
	
	public function __construct() {
		$this->css_list = array();
		$this->js_list = array();
	}

	public function css($src, $dest) {
		$handle = opendir($src);
		while($file = readdir($handle))
			if('.' != $file && '..' != $file)
				$this->css_list[] = $file;

		closedir($handle);
		
		sort($this->css_list);
		
		if(!file_exists($dest))
			mkdir($dest, 0755, TRUE);
		
		$css_package = fopen(rtrim($dest, '/') . '/main.css', 'w+');
		foreach((array)$this->css_list as $filename) {
			$css = file_get_contents($src . $filename);
			$css = $this->CssCompressor($css);
			fwrite($css_package, $css);
		}
		fclose($css_package);
	}
	
	// Css Compressor
	private function CssCompressor($css) {
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
				$this->js_list[] = $file;
		closedir($handle);
		
		sort($this->js_list);
		
		if(!file_exists($dest))
			mkdir($dest, 0755, TRUE);
		
		$js_package = fopen(rtrim($dest, '/') . '/main.js', 'w+');
		foreach((array)$this->js_list as $filename) {
			$handle = fopen($src . $filename, 'r');
			while($data = fread($handle, 1024))
				fwrite($js_package, $data, 1024);
			fwrite($js_package, "\n");
			fclose($handle);
		}
		fclose($js_package);
	}
}
