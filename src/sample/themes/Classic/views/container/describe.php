<?php
use Oni\Web\Helper;

$disqusShortname = $systemConfig['blog']['disqusShortname'];
?>
<div id="container_static">
    <article class="post_block">
        <h1 class="title"><?=$container['title']?></h1>
        <div class="content"><?=$container['content']?></div>
    </article>
    <?php if (null !== $disqusShortname && $container['withMessage']): ?>
    <hr>
    <div id="disqus_thread"></div>
    <?php endif; ?>
</div>
