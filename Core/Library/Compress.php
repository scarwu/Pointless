<?php

class compress {
	private $css_list;
	private $js_list;
	
	public function __construct() {
		$this->css_list = array();
		$this->js_list = array();
	}

	public function css() {
		echo "Compress Cascading Style Sheets";
		
		$handle = opendir(UI_CSS);
		while($file = readdir($handle))
			if('.' != $file && '..' != $file)
				array_push($this->css_list, $file);
		closedir($handle);
		
		sort($this->css_list);
		
		$css_package = fopen(HTDOCS . 'main.css', 'w+');
		foreach((array)$this->css_list as $filename) {
			$css = file_get_contents(UI_CSS . $filename);
			$css = $this->css_compressor($css);
			fwrite($css_package, $css);
		}
		fclose($css_package);
		
		echo "...OK!\n";
	}
	
	// Css Compressor
	private function css_compressor($css) {
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
	
	public function js() {
		echo "Compress Javascript";
		
		$handle = opendir(UI_JS);
		while($file = readdir($handle))
			if('.' != $file && '..' != $file)
				array_push($this->js_list, $file);
		closedir($handle);
		
		sort($this->js_list);
		
		$js_package = fopen(HTDOCS . 'main.js', 'w+');
		foreach((array)$this->js_list as $filename) {
			$handle = fopen(UI_JS . $filename, 'r');
			while($data = fread($handle, 1024))
				fwrite($js_package, $data, 1024);
			fwrite($js_package, "\n");
			fclose($handle);
		}
		fclose($js_package);
		
		echo "...OK!\n";
	}
}
