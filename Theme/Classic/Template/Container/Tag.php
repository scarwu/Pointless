<?php
$bar = sprintf('<span class="count">< %d / %d ></span>', $data['bar']['index'], $data['bar']['total']);
if($data['bar']['total'] != 1) {
	if($data['bar']['index'] == 1)
		$bar .= sprintf('<span class="new"></span><span class="old"><a href="/tag/%s">%s >></a></span>', $data['bar']['next']['url'], $data['bar']['next']['title']);
	elseif($data['bar']['index'] == $data['bar']['total'])
		$bar .= sprintf('<span class="new"><a href="/tag/%s"><< %s</a></span><span class="old"></span>', $data['bar']['prev']['url'], $data['bar']['prev']['title']);
	else {
		$bar .= sprintf('<span class="new"><a href="/tag/%s"><< %s</a></span>', $data['bar']['prev']['url'], $data['bar']['prev']['title']);
		$bar .= sprintf('<span class="old"><a href="/tag/%s">%s >></a></span>', $data['bar']['next']['url'], $data['bar']['next']['title']);
	}
}

$year_list = array();
foreach((array)$data['article_list'] as $article) {
	if(!isset($year_list[$article['year']]))
		$year_list[$article['year']] = array();
	
	if(!isset($year_list[$article['year']][$article['month']]))
		$year_list[$article['year']][$article['month']] = array();
	
	$year_list[$article['year']][$article['month']][] = $article;
}
krsort($year_list);
?>
<div id="tag">
	<div class="title"><?php echo $data['title']; ?></div>
	<?php
	foreach((array)$year_list as $year => $month_list) {
		echo '<div class="year_archive">';
		echo '<div class="year">' . $year . '</div>';
		foreach((array)$month_list as $month => $article_list) {
			echo '<div class="month_archive">';
			echo '<div class="month">' . $month . '</div>';
			echo '<div class="list">';
			foreach((array)$article_list as $article) {
				echo '<article>';
				echo '<span class="title">' . link_to(BLOG_PATH.'article/'.$article['url'], $article['title']) . '</span>';
				echo '<span class="archive">Archive: ' . link_to(BLOG_PATH.'archive/'.$article['year'], $article['year']) . '</span>';
				echo '<span class="category">Category: ' . link_to(BLOG_PATH.'category/'.$article['category'], $article['category']) . '</span>';
				echo '<span class="tag">Tag: ';
				foreach((array)$article['tag'] as $index => $tag)
					echo link_to(BLOG_PATH.'tag/'.$tag, $tag) . (count($article['tag'])-1 > $index ? ', ' : '');
				echo '</span>';
				echo '</article>';
			}
			echo '</div>';
			echo '</div>';
			echo '<hr>';
		}
		echo '</div>';
	}
	?>
	<div class="bar"><?php echo $bar; ?></div>
</div>