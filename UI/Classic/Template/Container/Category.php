<?php
$temp = array();
foreach((array)$data['article_list'] as $index => $article_info) {
	if(!isset($temp[$article_info['month']]))
		$temp[$article_info['month']] = array();
	$temp[$article_info['month']][] = $article_info;
}
krsort($temp);
?>
<div id="category">
	<div class="title"><?php echo $data['title']; ?></div>
	<?php
	foreach((array)$temp as $month => $article_list) {
		echo '<div class="month_archive">';
		echo '<div class="month">' . $month . '</div>';
		echo '<div class="list">';
		foreach((array)$article_list as $info) {
			echo '<article>';
			echo '<span class="title">' . link_to(BLOG_PATH.'article/'.$info['url'], $info['title']) . '</span>';
			// echo '<footer>';
			echo '<span class="archive">Archive: ' . link_to(BLOG_PATH.'archive/'.$info['year'], $info['year']) . '</span>';
			echo '<span class="tag">Tag: ';
			foreach((array)$info['tag'] as $index => $tag)
				echo link_to(BLOG_PATH.'tag/'.$tag, $tag) . (count($info['tag'])-1 > $index ? ', ' : '');
			echo '</span>';
			// echo '</footer>';
			echo '</article>';
		}
		echo '</div>';
		echo '</div>';
		echo '<hr>';
	}
	?>
</div>