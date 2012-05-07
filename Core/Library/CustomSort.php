<?php

function article_sort($list) {
	$result = array();
	
	if(!empty($list)) {
		foreach($list as $key => $value)
			$tmp[$key] = $value['date'] . ' ' . $value['time'];
		
		arsort($tmp);
		
		$result = array();
		foreach($tmp as $key => $value)
			$result[] = $list[$key];
	}
	
	return $result;
}

function count_sort($list) {
	$result = array();
	
	if(!empty($list)) {
		foreach($list as $key => $value)
			$result[$key] = count($value);
	
		arsort($result);
		
		foreach($result as $key => $value)
			$result[$key] = article_sort($list[$key]);
	}
	
	return $result;
}