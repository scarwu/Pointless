<div id="article">
	<article>
		<div class="title"><?=$data['title']?></div>
		<div class="info"></div>
		<div class="content"><?=$data['content']?></div>
	</article>
	<hr>
	<?php if(NULL != DISQUS_SHORTNAME && $data['message']): ?>
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