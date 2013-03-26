<?php
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
<div id="archive">
	<div class="title"><?=$data['title']?></div>
	<?php foreach((array)$year_list as $year => $month_list): ?>
	<div class="year_archive">
		<div class="year"><?=$year?></div>
		<?php foreach((array)$month_list as $month => $article_list): ?>
		<div class="month_archive">
			<div class="month"><?=$month?></div>
			<div class="list">
				<?php foreach((array)$article_list as $article): ?>
				<article>
					<span class="title">
						<?=linkTo(BLOG_PATH . 'article/' . $article['url'], $article['title'])?>
					</span>
					<span class="category">
						Category: <?=linkTo(BLOG_PATH . 'category/' . $article['category'], $article['category'])?>
					</span>
					<span class="tag">Tag: 
						<?php foreach((array)$article['tag'] as $index => $tag): ?>
						<?=linkTo(BLOG_PATH . 'tag/' . $tag, $tag) . (count($article['tag'])-1 > $index ? ', ' : '')?>
						<?php endforeach; ?>
					</span>
				</article>
				<?php endforeach; ?>
			</div>
		</div>
		<hr>
		<?php endforeach; ?>
	</div>
	<?php endforeach; ?>
	<div class="bar">
		<span class="new">
			<?=$data['bar']['total'] != 1 && $data['bar']['index'] != 1
				? linkTo(BLOG_PATH . 'archive/' . $data['bar']['prev']['url'], '<< ' . $data['bar']['prev']['title']): ''?>
		</span>
		<span class="old">
			<?=$data['bar']['total'] != 1 && $data['bar']['index'] != $data['bar']['total']
				? linkTo(BLOG_PATH . 'archive/' . $data['bar']['next']['url'], $data['bar']['next']['title'] . ' >>'): ''?>
		</span>
		<span class="count">< <?=$data['bar']['index']?> / <?=$data['bar']['total']?> ></span>
	</div>
</div>