<div id="article">
	<article>
		<div class="title"><?=$data['title']?></div>
		<div class="info">
			<span class="comment">
				<?=linkTo(BLOG_PATH . 'article/' . $data['url'] .'/#disqus_thread', '0 Comment')?>
			</span>
			<br />
			<span class="date"><?=$data['date']?></span>
			-
			<span class="category">
				Category: <?=linkTo(BLOG_PATH . "category/{$data['category']}", $data['category'])?>
			</span>
			-
			<span class="tag">
				Tag: 
				<?php foreach($data['tag'] as $index =>  $tag): ?>
				<span><?=linkTo(BLOG_PATH . "tag/$tag", $tag) . (count($data['tag'])-1 > $index ? ', ' : '')?></span>
				<?php endforeach; ?>
			</span>
		</div>
		<div class="content"><?=$data['content']?></div>
	</article>
	<hr>
	<div class="bar">
		<span class="new">
			<?=isset($data['bar']['prev'])
				? linkTo(BLOG_PATH . 'article/' . $data['bar']['prev']['url'], '<< ' . $data['bar']['prev']['title']): ''?>
		</span>
		<span class="old">
			<?=isset($data['bar']['next'])
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