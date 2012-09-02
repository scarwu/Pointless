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
?>
<div id="page">
	<?php
	foreach((array)$data['article_list'] as $article) {
		echo '<article>';
		echo '<div class="title">' . link_to(BLOG_PATH.'article/'.$article['url'], $article['title']) . '</div>';
		echo '<div class="info">';
		echo '<span class="date">Date: ' . $article['date'] . '</span>';
		echo '</div>';
		echo '<div class="content">' . preg_replace('/<!--more-->(.|\n)*/', '', $article['content']) . '</div>';
		echo '<a class="more" href="' . BLOG_PATH . 'article/' . $article['url'] . '">Read more ...</a>';
		echo '</article>';
		echo '<hr>';
	}
	?>
	<div class="bar"><?php echo $bar; ?></div>
</div>