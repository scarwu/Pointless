<?php
	$bar = sprintf('<span class="count">< %d / %d ></span>', $data['bar']['index'], $data['bar']['total']);
	if($data['bar']['total'] != 1) {
		if($data['bar']['index'] == 1)
			$bar .= sprintf('<span class="new"></span><span class="old"><a href="/article/%s">%s >></a></span>', $data['bar']['next']['url'], $data['bar']['next']['title']);
		elseif($data['bar']['index'] == $data['bar']['total'])
			$bar .= sprintf('<span class="new"><a href="/article/%s"><< %s</a></span><span class="old"></span>', $data['bar']['prev']['url'], $data['bar']['prev']['title']);
		else {
			$bar .= sprintf('<span class="new"><a href="/article/%s"><< %s</a></span>', $data['bar']['prev']['url'], $data['bar']['prev']['title']);
			$bar .= sprintf('<span class="old"><a href="/article/%s">%s >></a></span>', $data['bar']['next']['url'], $data['bar']['next']['title']);
		}
	}
?>
<div id="article">
	<article>
		<div class="title"><?php echo $data['title']; ?></div>
		<div class="info">
		<?php echo '<span class="date">Date: ' . $data['date'] . '</span>'; ?>
		</div>
		<div class="content"><?php echo $data['content']; ?></div>
	</article>
	<hr>
	<div class="bar"><?php echo $bar; ?></div>
	<?php if(NULL != DISQUS_SHORTNAME): ?>
	<!-- DISQUS -->
	<div id="disqus_thread"></div>
	<script type="text/javascript">
	    var disqus_shortname = '<?php echo DISQUS_SHORTNAME; ?>';
	    (function() {
	        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
	        dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
	        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
	    })();
	</script>
	<?php endif; ?>
</div>