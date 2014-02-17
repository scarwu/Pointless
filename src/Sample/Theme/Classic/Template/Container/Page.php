<div id="page">
	<?php foreach((array)$data['list'] as $article): ?>
	<article>
		<div class="title">
			<?=linkTo("{$data['base']}article/{$article['url']}", $article['title'])?>
		</div>
		<div class="info">
			<?php if(NULL != $data['disqus_shortname']): ?>
			<span class="comments">
				<a href="<?=linkEncode("{$data['base']}article/{$article['url']}/")?>#disqus_thread">0 Comment</a>
			</span>
			<br />
			<?php endif; ?>
			<span class="date"><?=$article['date']?></span>
			-
			<span class="category">
				Category: <?=linkTo("{$data['base']}category/{$article['category']}", $article['category'])?>
			</span>
			-
			<span class="tag">
				Tag: 
				<?php foreach((array)$article['tag'] as $index => $tag): ?>
				<?php $article['tag'][$index] = linkTo("{$data['base']}tag/$tag", $tag); ?>
				<?php endforeach; ?>
				<?=join($article['tag'], ', ')?>
			</span>
		</div>
		<div class="content">
			<?=preg_replace('/<!--more-->(.|\n)*/', '', $article['content'])?>
		</div>
		<a class="more" href="<?="{$data['base']}article/{$article['url']}"?>">
			Read more
		</a>
	</article>
	<hr>
	<?php endforeach; ?>
	<div class="bar">
		<span class="new">
			<?=isset($data['bar']['n_path'])
				? linkTo($data['bar']['n_path'], '<< Newer Posts'): ''?>
		</span>
		<span class="old">
			<?=isset($data['bar']['p_path'])
				? linkTo($data['bar']['p_path'], 'Older Posts >>'): ''?>
		</span>
		<span class="count">
			<?="{$data['bar']['index']} / {$data['bar']['total']}"?>
		</span>
	</div>
</div>
<?php if(NULL != $data['disqus_shortname']): ?>
<script type="text/javascript">
	var disqus_shortname = '<?=$data['disqus_shortname']?>';
	(function() {
		var count = document.createElement('script');
		count.async = true;
		count.type = 'text/javascript';
		count.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
		(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(count);
	}());
</script>
<?php endif; ?>