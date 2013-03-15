<?php
$index = $data['bar']['index'];
$total = $data['bar']['total'];

$next = isset($data['bar']['next']) ? $data['bar']['next'] : NULL;
$prev = isset($data['bar']['prev']) ? $data['bar']['prev'] : NULL;

$bar = sprintf('<span class="count">< %d / %d ></span>', $index, $total);

if($total != 1) {
	if($index == 1)
		$old = sprintf('<a href="/page/%s">Older Posts >></a>', $index+1);
	elseif($data['bar']['index'] == $data['bar']['total'])
		$new = sprintf('<a href="/page/%s"><< Newer Posts</a>', $index-1);
	else {
		$old = sprintf('<a href="/page/%s">Older Posts >></a>', $index+1);
		$new = sprintf('<a href="/page/%s"><< Newer Posts</a>', $index-1);
	}
	
	$bar .= sprintf('<span class="new">%s</span>', isset($new) ? $new : '');
	$bar .= sprintf('<span class="old">%s</span>', isset($old) ? $old : '');
}

$content = '';
foreach((array)$data['article_list'] as $article) {
	$content .= '<article>';
	$content .= '<div class="title">' . linkTo(BLOG_PATH . 'article/' . $article['url'], $article['title']) . '</div>';
	$content .= '<div class="info">';
	$content .= '<span class="date">Date: ' . $article['date'] . '</span>';
	$content .= '</div>';
	$content .= '<div class="content">' . preg_replace('/<!--more-->(.|\n)*/', '', $article['content']) . '</div>';
	$content .= '<a class="more" href="' . BLOG_PATH . 'article/' . $article['url'] . '">Read more ...</a>';
	$content .= '</article>';
	$content .= '<hr>';
}
?>
<div id="page">
	<?=$content?>
	<div class="bar"><?=$bar?></div>
</div>