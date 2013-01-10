<?php

function linkTo($link, $name) {
	return sprintf('<a href="%s">%s</a>', $link, $name);
}

function bindData($data, $path) {
	ob_start();
	include $path;
	$result = ob_get_contents();
	ob_end_clean();
	
	return $result;
}

// FIXME and Theme/Script/*/*.php
function writeTo($data, $path) {
	if(!preg_match('/\.html$/', $path)) {
		if(!file_exists($path))
			mkdir($path, 0755, TRUE);
		$path = $path . '/index.html';
	}

	$handle = fopen($path, 'w+');
	fwrite($handle, $data);
	fclose($handle);
}

function recursiveCopy($src, $dest) {
	if(file_exists($src)) {
		if(is_dir($src)) {
			if(!file_exists($dest))
				mkdir($dest, 0755, TRUE);
			$handle = @opendir($src);
			while($file = readdir($handle))
				if($file != '.' && $file != '..' && $file != '.git')
					recursiveCopy($src . '/' . $file, $dest . '/' . $file);
			closedir($handle);
		}
		else
			copy($src, $dest);
	}
}

function recursiveRemove($path = NULL) {
	if(file_exists($path)) {
		if(is_dir($path)) {
			$handle = @opendir($path);
			while($file = readdir($handle))
				if($file != '.' && $file != '..' && $file != '.git')
					recursiveRemove($path . '/' . $file);
			closedir($handle);
			
			if($path != PUBLIC_FOLDER)
				return rmdir($path);
		}
		else
			return unlink($path);
	}
}

function articleSort($list) {
	$result = array();
	
	if(!empty($list)) {
		foreach($list as $key => $value)
			$tmp[$key] = $value['date'] . $value['time'];
		
		arsort($tmp);
		
		$result = array();
		foreach($tmp as $key => $value)
			$result[] = $list[$key];
	}
	
	return $result;
}

function countSort($list) {
	$result = array();
	
	if(!empty($list)) {
		foreach($list as $key => $value)
			$result[$key] = count($value);
	
		arsort($result);
		
		foreach($result as $key => $value)
			$result[$key] = articleSort($list[$key]);
	}
	
	return $result;
}