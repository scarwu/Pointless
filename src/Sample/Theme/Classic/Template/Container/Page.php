<div id="page">
	<?php foreach((array)$data['list'] as $article): ?>
	<article>
		<div class="title"><?=linkTo(BLOG_PATH . 'article/' . $article['url'], $article['title'])?></div>
		<div class="info">
			<span class="comments">
				<a href="<?=linkEncode(BLOG_PATH . "article/{$article['url']}/")?>#disqus_thread">0 Comment</a>
			</span>
			<br />
			<span class="date"><?=$article['date']?></span>
			-
			<span class="category">
				Category: <?=linkTo(BLOG_PATH . "category/{$article['category']}", $article['category'])?>
			</span>
			-
			<span class="tag">
				Tag: 
				<?php foreach($article['tag'] as $index =>  $tag): ?>
				<span><?=linkTo(BLOG_PATH . "tag/$tag", $tag) . (count($article['tag'])-1 > $index ? ', ' : '')?></span>
				<?php endforeach; ?>
			</span>
		</div>
		<div class="content"><?=preg_replace('/<!--more-->(.|\n)*/', '', $article['content'])?></div>
		<a class="more" href="<?=BLOG_PATH . 'article/' . $article['url']?>">Read more</a>
	</article>
	<hr>
	<?php endforeach; ?>
	<div class="bar">
		<span class="new">
			<?=$data['bar']['index'] > 1
				? linkTo(BLOG_PATH . 'page/' . ($data['bar']['index']-1), '<< Newer Posts'): ''?>
		</span>
		<span class="old">
			<?=$data['bar']['index'] < $data['bar']['total']
				? linkTo(BLOG_PATH . 'page/' . ($data['bar']['index']+1), 'Older Posts >>'): ''?>
		</span>
		<span class="count">&lt; <?=$data['bar']['index']?> / <?=$data['bar']['total']?> &gt;</span>
	</div>
</div>
<?php if(NULL != DISQUS_SHORTNAME): ?>
<script type="text/javascript">
	var disqus_shortname = '<?=DISQUS_SHORTNAME?>';
	(function() {
		var count = document.createElement('script');
		count.async = true;
		count.type = 'text/javascript';
		count.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
		(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(count);
	}());
</script>
<?php endif; ?>