<div id="page">
	<?php foreach((array)$data['article_list'] as $article): ?>
	<article>
		<div class="title"><?=linkTo(BLOG_PATH . 'article/' . $article['url'], $article['title'])?></div>
		<div class="info">
			<span class="date"><?=$article['date']?></span>
			<br />
			<span class="comments">
				<?=linkTo(BLOG_PATH . 'article/' . $article['url'] . '/#disqus_thread', '0 Comments')?>
			</span>
		</div>
		<div class="content"><?=preg_replace('/<!--more-->(.|\n)*/', '', $article['content'])?></div>
		<a class="more" href="<?=BLOG_PATH . 'article/' . $article['url']?>">Read more</a>
	</article>
	<hr>
	<?php endforeach; ?>
	<div class="bar">
		<span class="new">
			<?=$data['bar']['total'] != 1 && $data['bar']['index'] != 1
				? linkTo(BLOG_PATH . 'page/' . ($data['bar']['index']-1), '<< Newer Posts'): ''?>
		</span>
		<span class="old">
			<?=$data['bar']['total'] != 1 && $data['bar']['index'] != $data['bar']['total']
				? linkTo(BLOG_PATH . 'page/' . ($data['bar']['index']+1), 'Older Posts >>'): ''?>
		</span>
		<span class="count">< <?=$data['bar']['index']?> / <?=$data['bar']['total']?> ></span>
	</div>
</div>