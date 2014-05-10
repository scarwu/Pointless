<div id="static">
    <article>
        <div class="title"><?=$post['title']?></div>
        <div class="info"></div>
        <div class="content"><?=$post['content']?></div>
    </article>
    <?php if (null !== $blog['disqus_shortname'] && $post['message']): ?>
    <hr>
    <div id="disqus_thread"></div>
    <?php endif; ?>
</div>
