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

// FIXME and Theme/Script/*/*.php
function write_to($data, $path) {
	if(!preg_match('/\.html$/', $path)) {
		if(!file_exists($path))
			mkdir($path, 0755, TRUE);
		$path = $path . SEPARATOR .  'index.html';
	}

	$handle = fopen($path, 'w+');
	fwrite($handle, $data);
	fclose($handle);
}

function recursive_copy($src, $dest) {
	if(is_dir($src)) {
		if(!file_exists($dest))
			mkdir($dest, 0755, TRUE);
		$handle = @opendir($src);
		while($file = readdir($handle))
			if($file != '.' && $file != '..' && $file != '.git')
				recursive_copy($src . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file);
		closedir($handle);
	}
	else
		copy($src, $dest);
}