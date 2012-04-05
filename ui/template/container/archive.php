<?php
$temp = array();
foreach((array)$data['article_list'] as $index => $article_info) {
	$article_month = substr($article_info['post_date'], 5, 2);
	if(!isset($temp[$article_month]))
		$temp[$article_month] = array();
	array_push($temp[$article_month], $article_info);
}
?>

<div id="archive">
	<div class="title"><?php echo $data['title']; ?></div>
	<?php
	echo '<div class="month">';
	foreach((array)$temp as $month => $article_list) {
		echo '<div>' . $month . '</div>';
		echo '<div>';
		foreach((array)$article_list as $info) {
			echo '<article>';
			echo '<span>' . $info['title'] . '</span>';
			// echo '<footer>';
			echo '<span>Category:' . $info['category'] . '</span>';
			// echo '</footer>';
			echo '</article>';
		}
		echo '</div>';
	}
	echo '</div>';
	?>
</div>