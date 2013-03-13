<?php
$index = $data['bar']['index'];
$total = $data['bar']['total'];

$next = isset($data['bar']['next']) ? $data['bar']['next'] : NULL;
$prev = isset($data['bar']['prev']) ? $data['bar']['prev'] : NULL;

$bar = '';
if($total != 1) {
	if($index == 1)
		$old = sprintf('<a href="/article/%s">%s >></a>', $next['url'], $next['title']);
	elseif($data['bar']['index'] == $data['bar']['total'])
		$new = sprintf('<a href="/article/%s"><< %s</a>', $prev['url'], $prev['title']);
	else {
		$old = sprintf('<a href="/article/%s">%s >></a>', $next['url'], $next['title']);
		$new = sprintf('<a href="/article/%s"><< %s</a>', $prev['url'], $prev['title']);
	}
	
	$bar .= sprintf('<span class="new">%s</span>', isset($new) ? $new : '');
	$bar .= sprintf('<span class="old">%s</span>', isset($old) ? $old : '');
	$bar .= sprintf('<span class="count">< %d / %d ></span>', $index, $total);
}
?>
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
	<div class="bar"><?=$bar?></div>
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