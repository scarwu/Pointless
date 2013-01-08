<?php
$index = $data['bar']['index'];
$total = $data['bar']['total'];

$next = isset($data['bar']['next']) ? $data['bar']['next'] : NULL;
$prev = isset($data['bar']['prev']) ? $data['bar']['prev'] : NULL;

$bar = sprintf('<span class="count">< %d / %d ></span>', $index, $total);

if($total != 1) {
	if($index == 1)
		$old = sprintf('<a href="/tag/%s">%s >></a>', $next['url'], $next['title']);
	elseif($data['bar']['index'] == $data['bar']['total'])
		$new = sprintf('<a href="/tag/%s"><< %s</a>', $prev['url'], $prev['title']);
	else {
		$old = sprintf('<a href="/tag/%s">%s >></a>', $next['url'], $next['title']);
		$new = sprintf('<a href="/tag/%s"><< %s</a>', $prev['url'], $prev['title']);
	}
	
	$bar .= sprintf('<span class="new">%s</span>', isset($new) ? $new : '');
	$bar .= sprintf('<span class="old">%s</span>', isset($old) ? $old : '');
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

$content = '';
foreach((array)$year_list as $year => $month_list) {
	$content .= '<div class="year_archive">';
	$content .= '<div class="year">' . $year . '</div>';
	foreach((array)$month_list as $month => $article_list) {
		$content .= '<div class="month_archive">';
		$content .= '<div class="month">' . $month . '</div>';
		$content .= '<div class="list">';
		foreach((array)$article_list as $article) {
			$content .= '<article>';
			$content .= '<span class="title">' . linkTo(BLOG_PATH.'article/'.$article['url'], $article['title']) . '</span>';
			$content .= '<span class="archive">Archive: ' . linkTo(BLOG_PATH.'archive/'.$article['year'], $article['year']) . '</span>';
			$content .= '<span class="category">Category: ' . linkTo(BLOG_PATH.'category/'.$article['category'], $article['category']) . '</span>';
			$content .= '<span class="tag">Tag: ';
			foreach((array)$article['tag'] as $index => $tag)
				$content .= linkTo(BLOG_PATH.'tag/'.$tag, $tag) . (count($article['tag'])-1 > $index ? ', ' : '');
			$content .= '</span>';
			$content .= '</article>';
		}
		$content .= '</div>';
		$content .= '</div>';
		$content .= '<hr>';
	}
	$content .= '</div>';
}
?>
<div id="tag">
	<div class="title"><?php echo $data['title']; ?></div>
	<?php echo $content; ?>
	<div class="bar"><?php echo $bar; ?></div>
</div>