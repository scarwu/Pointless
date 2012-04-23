<?php
$temp = array();
foreach((array)$data['article_list'] as $index => $article_info) {
	if(!isset($temp[$article_info['month']]))
		$temp[$article_info['month']] = array();
	array_push($temp[$article_info['month']], $article_info);
}
krsort($temp);
?>

<div id="archive">
	<div class="title"><?php echo $data['title']; ?></div>
	<?php
	
	foreach((array)$temp as $month => $article_list) {
		echo '<div class="month_archive">';
		echo '<div class="month">' . $month . '</div>';
		echo '<div class="list">';
		foreach((array)$article_list as $info) {
			echo '<article>';
			echo '<span class="title"><a href="' . BLOG_PATH . 'article/' . $info['url'] . '">' . $info['title'] . '</a></span>';
			// echo '<footer>';
			echo '<span class="category">Category: <a href="'. BLOG_PATH . 'category/' . $info['category'] .'">' . $info['category'] . '</a></span>';
			echo '<span class="tag">Tag: ';
			foreach((array)$info['tag'] as $index => $tag)
				echo '<a href="'. BLOG_PATH . 'tag/' . $tag .'">' . $tag . '</a>' . (count($info['tag'])-1 > $index ? ', ' : '');
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