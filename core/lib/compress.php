<?php

class compress {
	private $css_list;
	private $js_list;
	
	public function __construct() {
		$this->css_list = array();
		$this->js_list = array();
	}
	
	public function run() {
		$this->css();
		$this->js();
	}
	
	private function css() {
		$handle = opendir(UI_CSS);
		while($file = readdir($handle))
			if('.' != $file && '..' != $file)
				array_push($this->css_list, $file);
		closedir($handle);
		
		sort($this->css_list);
		
		$css_package = fopen(STATIC_FOLDER . 'main.css', 'w+');
		foreach((array)$this->css_list as $filename) {
			$handle = fopen(UI_CSS . $filename, 'r');
			while($data = fread($handle, 1024))
				fwrite($css_package, $data, 1024);
			fwrite($css_package, "\n");
			fclose($handle);
		}
		fclose($css_package);
	}
	
	private function js() {
		$handle = opendir(UI_JS);
		while($file = readdir($handle))
			if('.' != $file && '..' != $file)
				array_push($this->js_list, $file);
		closedir($handle);
		
		sort($this->js_list);
		
		$js_package = fopen(STATIC_FOLDER . 'main.js', 'w+');
		foreach((array)$this->js_list as $filename) {
			$handle = fopen(UI_JS . $filename, 'r');
			while($data = fread($handle, 1024))
				fwrite($js_package, $data, 1024);
			fwrite($js_package, "\n");
			fclose($handle);
		}
		fclose($js_package);
	}
}
