<?php

function article_sort($list) {
	krsort($list);
	return $list;
}

function count_sort($list) {
	$result = array();
	foreach($list as $key => $value)
		$result[$key] = count($value);

	arsort($result);
	
	foreach($result as $key => $value)
		$result[$key] = article_sort($list[$key]);
	
	return $result;
}