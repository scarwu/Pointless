<div id="archive">
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
						<?=linkTo("{$data['base']}{$article['url']}", $article['title'])?>
					</span>
					<span class="category">
						Category: <?=linkTo("{$data['base']}category/{$article['category']}", $article['category'])?>
					</span>
					<span class="tag">Tag: 
						<?php foreach((array)$article['tag'] as $index => $tag): ?>
						<?=linkTo("{$data['base']}tag/$tag", $tag) . (count($article['tag']) - 1 > $index ? ', ' : '')?>
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
			<?=isset($data['bar']['p_path'])
				? linkTo($data['bar']['p_path'], "<< {$data['bar']['p_title']}"): ''?>
		</span>
		<span class="old">
			<?=isset($data['bar']['n_path'])
				? linkTo($data['bar']['n_path'], "{$data['bar']['n_title']} >>"): ''?>
		</span>
		<span class="count">&lt; <?="{$data['bar']['index']} / {$data['bar']['total']}"?> &gt;</span>
	</div>
</div>