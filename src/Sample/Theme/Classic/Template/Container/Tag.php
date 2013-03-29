<div id="tag">
	<div class="title"><?=$data['title']?></div>
	<?php foreach((array)$data['date_list'] as $year => $month_list): ?>
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
					<span class="archive">
						Archive: <?=linkTo(BLOG_PATH . 'archive/' . $article['year'], $article['year'])?>
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
				? linkTo(BLOG_PATH . 'tag/' . $data['bar']['prev']['url'], '<< ' . $data['bar']['prev']['title']): ''?>
		</span>
		<span class="old">
			<?=$data['bar']['total'] != 1 && $data['bar']['index'] != $data['bar']['total']
				? linkTo(BLOG_PATH . 'tag/' . $data['bar']['next']['url'], $data['bar']['next']['title'] . ' >>'): ''?>
		</span>
		<span class="count">< <?=$data['bar']['index']?> / <?=$data['bar']['total']?> ></span>
	</div>
</div>