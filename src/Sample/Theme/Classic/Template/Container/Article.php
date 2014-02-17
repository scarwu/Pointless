<div id="article">
	<article>
		<div class="title"><?=$data['title']?></div>
		<div class="info">
			<?php if(NULL != $data['disqus_shortname']): ?>
			<span class="comment">
				<a href="<?=linkEncode("{$data['base']}{$data['path']}/")?>#disqus_thread">
					0 Comment
				</a>
			</span>
			<br />
			<?php endif; ?>
			<span class="date"><?=$data['date']?></span>
			-
			<span class="category">
				Category:
				<?=linkTo("{$data['base']}category/{$data['category']}", $data['category'])?>
			</span>
			-
			<span class="tag">
				Tag: 
				<?php foreach((array)$data['tag'] as $index => $tag): ?>
				<?php $data['tag'][$index] = linkTo("{$data['base']}tag/$tag", $tag); ?>
				<?php endforeach; ?>
				<?=join($data['tag'], ', ')?>
			</span>
		</div>
		<div class="content">
			<?=$data['content']?>
		</div>
	</article>
	<hr>
	<div class="bar">
		<span class="new">
			<?=isset($data['bar']['p_path'])
				? linkTo($data['bar']['p_path'], "<< {$data['bar']['p_title']}"): ''?>
		</span>
		<span class="old">
			<?=isset($data['bar']['n_path'])
				? linkTo($data['bar']['n_path'], "{$data['bar']['n_title']} >>"): ''?>
		</span>
		<span class="count">
			<?="{$data['bar']['index']} / {$data['bar']['total']}"?>
		</span>
	</div>
	<?php if(NULL != $data['disqus_shortname']): ?>
	<hr>
	<div id="disqus_thread"></div>
	<script type="text/javascript">
		var disqus_shortname = '<?=$data['disqus_shortname']?>';
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