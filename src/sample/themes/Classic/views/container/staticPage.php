<?php
use Pointless\Library\Helper;
?>
<div id="container_static">
    <article class="post_block">
        <h1 class="title"><?=$post['title']?></h1>
        <div class="content"><?=$post['content']?></div>
    </article>
    <?php if (null !== $blog['disqus_shortname'] && $post['message']): ?>
    <hr>
    <div id="disqus_thread"></div>
    <?php endif; ?>
</div>
