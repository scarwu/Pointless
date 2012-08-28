<?php
$temp = array();
foreach((array)$data['article_list'] as $index => $article_info) {
	if(!isset($temp[$article_info['month']]))
		$temp[$article_info['month']] = array();
	$temp[$article_info['month']][] = $article_info;
}
krsort($temp);

$bar = sprintf('<span class="count">< %d / %d ></span>', $data['bar']['index'], $data['bar']['total']);
if($data['bar']['total'] != 1) {
	if($data['bar']['index'] == 1)
		$bar .= sprintf('<span class="new"></span><span class="old"><a href="/archive/%s">%s >></a></span>', $data['bar']['next']['url'], $data['bar']['next']['title']);
	elseif($data['bar']['index'] == $data['bar']['total'])
		$bar .= sprintf('<span class="new"><a href="/archive/%s"><< %s</a></span><span class="old"></span>', $data['bar']['prev']['url'], $data['bar']['prev']['title']);
	else {
		$bar .= sprintf('<span class="new"><a href="/archive/%s"><< %s</a></span>', $data['bar']['prev']['url'], $data['bar']['prev']['title']);
		$bar .= sprintf('<span class="old"><a href="/archive/%s">%s >></a></span>', $data['bar']['next']['url'], $data['bar']['next']['title']);
	}
}
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
			echo '<span class="title">' . link_to(BLOG_PATH.'article/'.$info['url'], $info['title']) . '</span>';
			// echo '<footer>';
			echo '<span class="category">Category: ' . link_to(BLOG_PATH.'category/'.$info['category'], $info['category']) . '</span>';
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
	<div class="bar"><?php echo $bar; ?></div>
</div>