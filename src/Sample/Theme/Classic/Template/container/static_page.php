<div id="static">
    <article>
        <div class="title"><?=$post['title']?></div>
        <div class="info"></div>
        <div class="content"><?=$post['content']?></div>
    </article>
    <?php if (null !== $blog['disqus_shortname'] && $post['message']): ?>
    <hr>
    <div id="disqus_thread"></div>
    <script type="text/javascript">
        var disqus_shortname = '<?=$blog['disqus_shortname']?>';
        (function () {
            var embed = document.createElement('script');
            embed.async = true;
            embed.type = 'text/javascript';
            embed.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(embed);
        })();
    </script>
    <?php endif; ?>
</div>
