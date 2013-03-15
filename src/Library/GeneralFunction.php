<?php
/**
 * General Function
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

/**
 * Create Link
 *
 * @param string
 * @param string
 * @return string
 */
function linkTo($link, $name) {
	return sprintf('<a href="%s">%s</a>', $link, $name);
}

/**
 * Create HTML Link
 *
 * @param string
 * @param string
 * @return string
 */
function bindData($data, $path) {
	ob_start();
	include $path;
	$result = ob_get_contents();
	ob_end_clean();
	
	return $result;
}

/**
 * Write Data to File
 *
 * @param string
 * @param string
 */
function writeTo($data, $path) {
	// FIXME and Theme/Script/*/*.php
	if(!preg_match('/\.(html|xml)$/', $path)) {
		if(!file_exists($path))
			mkdir($path, 0755, TRUE);
		$path = $path . '/index.html';
	}

	$handle = fopen($path, 'w+');
	fwrite($handle, $data);
	fclose($handle);
}

/**
 * Recursive Copy
 *
 * @param string
 * @param string
 */
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

/**
 * Recursive Remove
 *
 * @param string
 * @param string
 * @return boolean
 */
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

/**
 * Sort Using Article's Date
 *
 * @param array
 * @return array
 */
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

/**
 * Sort Using Article's Count
 *
 * @param array
 * @return array
 */
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