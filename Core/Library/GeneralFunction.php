<?php

function link_to($link, $name) {
	return sprintf('<a href="%s">%s</a>', $link, $name);
}

function bind_data($data, $path) {
	ob_start();
	include $path;
	$result = ob_get_contents();
	ob_end_clean();
	
	return $result;
}

function write_to($data, $path) {
	if(!file_exists($path))
		mkdir($path, 0755, TRUE);
	
	$handle = fopen($path . 'index.html', 'w+');
	fwrite($handle, $data);
	fclose($handle);
}