<div id="article">
	<article>
		<div class="title"><?=$data['title']?></div>
		<div class="info">
			<span class="date"><?=$data['date']?></span>
			<br />
			<span class="comments"><?=linkTo(BLOG_PATH . 'article/' . $data['url'] .'/#disqus_thread', '0 Comments')?></span>
		</div>
		<div class="content"><?=$data['content']?></div>
	</article>
	<hr>
	<div class="bar">
		<span class="new">
			<?=$data['bar']['total'] != 1 && $data['bar']['index'] != 1
				? linkTo(BLOG_PATH . 'article/' . $data['bar']['prev']['url'], '<< ' . $data['bar']['prev']['title']): ''?>
		</span>
		<span class="old">
			<?=$data['bar']['total'] != 1 && $data['bar']['index'] != $data['bar']['total']
				? linkTo(BLOG_PATH . 'article/' . $data['bar']['next']['url'], $data['bar']['next']['title'] . ' >>'): ''?>
		</span>
		<span class="count">< <?=$data['bar']['index']?> / <?=$data['bar']['total']?> ></span>
	</div>
	<?php if(NULL != DISQUS_SHORTNAME): ?>
	<hr>
	<!-- DISQUS -->
	<div id="disqus_thread"></div>
	<script type="text/javascript">
		var disqus_shortname = '<?=DISQUS_SHORTNAME?>';
		(function() {
			var embed = document.createElement('script');
			embed.type = 'text/javascript';
			embed.async = true;
			embed.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(embed);

			var count = document.createElement('script');
			count.async = true;
			count.type = 'text/javascript';
			count.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(count);
		}());
	</script>
	<?php endif; ?>
</div>