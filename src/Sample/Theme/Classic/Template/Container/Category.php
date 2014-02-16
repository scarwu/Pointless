<div id="category">
	<div class="title"><?=$data['title']?></div>
	<?php foreach((array)$data['list'] as $year => $month_list): ?>
	<div class="year_archive">
		<div class="year"><?=$year?></div>
		<?php foreach((array)$month_list as $month => $article_list): ?>
		<div class="month_archive">
			<div class="month"><?=$month?></div>
			<div class="list">
				<?php foreach((array)$article_list as $article): ?>
				<article>
					<span class="title">
						<?=linkTo("{$data['config']['blog_base']}article/{$article['url']}", $article['title'])?>
					</span>
					<span class="archive">
						Archive: <?=linkTo("{$data['config']['blog_base']}archive/{$article['year']}", $article['year'])?>
					</span>
					<span class="tag">Tag: 
						<?php foreach((array)$article['tag'] as $index => $tag): ?>
						<?=linkTo("{$data['config']['blog_base']}tag/$tag", $tag) . (count($article['tag']) - 1 > $index ? ', ' : '')?>
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
			<?=isset($data['bar']['prev'])
				? linkTo("{$data['config']['blog_base']}category/{$data['bar']['prev']['url']}", "<< {$data['bar']['prev']['title']}") : ''?>
		</span>
		<span class="old">
			<?=isset($data['bar']['next'])
				? linkTo("{$data['config']['blog_base']}category/{$data['bar']['next']['url']}", "{$data['bar']['next']['title']} >>") : ''?>
		</span>
		<span class="count">&lt; <?="{$data['bar']['index']} / {$data['bar']['total']}"?> &gt;</span>
	</div>
</div>