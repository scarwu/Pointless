<div id="article">
	<article>
		<div class="title"><?php echo $data['title']; ?></div>
		<div class="info">
		<?php echo '<span class="date">Date: ' . $data['date'] . '</span>'; ?>
		</div>
		<div class="content"><?php echo $data['content']; ?></div>
	</article>
	<hr>
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
</div>