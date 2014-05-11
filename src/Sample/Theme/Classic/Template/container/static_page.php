<div id="container_static">
    <article class="post_block">
        <div class="title"><?=$post['title']?></div>
        <div class="content"><?=$post['content']?></div>
    </article>
    <?php if (null !== $blog['disqus_shortname'] && $post['message']): ?>
    <hr>
    <div id="disqus_thread"></div>
    <?php endif; ?>
</div>
