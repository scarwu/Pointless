<div id="page">
	<?php foreach((array)$data['list'] as $article): ?>
	<article>
		<div class="title"><?=linkTo("{$data['config']['blog_base']}article/{$article['url']}", $article['title'])?></div>
		<div class="info">
			<?php if(NULL != $data['config']['disqus_shortname']): ?>
			<span class="comments">
				<a href="<?=linkEncode("{$data['config']['blog_base']}article/{$article['url']}/")?>#disqus_thread">0 Comment</a>
			</span>
			<br />
			<?php endif; ?>
			<span class="date"><?=$article['date']?></span>
			-
			<span class="category">
				Category: <?=linkTo("{$data['config']['blog_base']}category/{$article['category']}", $article['category'])?>
			</span>
			-
			<span class="tag">
				Tag: 
				<?php foreach($article['tag'] as $index =>  $tag): ?>
				<span><?=linkTo("{$data['config']['blog_base']}tag/$tag", $tag) . (count($article['tag'])-1 > $index ? ', ' : '')?></span>
				<?php endforeach; ?>
			</span>
		</div>
		<div class="content"><?=preg_replace('/<!--more-->(.|\n)*/', '', $article['content'])?></div>
		<a class="more" href="<?="{$data['config']['blog_base']}article/{$article['url']}"?>">Read more</a>
	</article>
	<hr>
	<?php endforeach; ?>
	<div class="bar">
		<span class="new">
			<?=$data['bar']['index'] > 1
				? linkTo("{$data['config']['blog_base']}page/" . ($data['bar']['index'] - 1), '<< Newer Posts'): ''?>
		</span>
		<span class="old">
			<?=$data['bar']['index'] < $data['bar']['total']
				? linkTo("{$data['config']['blog_base']}page/" . ($data['bar']['index'] + 1), 'Older Posts >>'): ''?>
		</span>
		<span class="count">&lt; <?="{$data['bar']['index']} / {$data['bar']['total']}"?> &gt;</span>
	</div>
</div>
<?php if(NULL != $data['config']['disqus_shortname']): ?>
<script type="text/javascript">
	var disqus_shortname = '<?=$data['config']['disqus_shortname']?>';
	(function() {
		var count = document.createElement('script');
		count.async = true;
		count.type = 'text/javascript';
		count.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
		(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(count);
	}());
</script>
<?php endif; ?>