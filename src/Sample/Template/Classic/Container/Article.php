<?php
$index = $data['bar']['index'];
$total = $data['bar']['total'];

$next = isset($data['bar']['next']) ? $data['bar']['next'] : NULL;
$prev = isset($data['bar']['prev']) ? $data['bar']['prev'] : NULL;

$bar = sprintf('<span class="count">< %d / %d ></span>', $index, $total);

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
}
?>
<div id="article">
	<article>
		<div class="title"><?=$data['title']?></div>
		<div class="info">
			<span class="date">Date: <?=$data['date']?></span>
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
	        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
	        dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
	        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
	    })();
	</script>
	<?php endif; ?>
</div>