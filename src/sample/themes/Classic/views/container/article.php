<?php
use Pointless\Library\Helper;
?>
<div id="container_article">
    <article class="post_block">
        <h1 class="title"><?=$post['title']?></h1>
        <div class="info">
            <?php if (null !== $blog['disqus_shortname'] && $post['message']): ?>
            <span class="comment">
                <i class="fa fa-comment"></i>
                <a href="<?=Helper::linkEncode("{$blog['base']}{$post['url']}/")?>#disqus_thread">0 Comment</a>
            </span>
            <?php endif; ?>
            <span class="date">
                <i class="fa fa-calendar"></i>
                <?=Helper::linkTo("{$blog['base']}archive/{$post['year']}/", $post['date'])?>
            </span>
            <span class="category">
                <i class="fa fa-folder-open"></i>
                <?=Helper::linkTo("{$blog['base']}category/{$post['category']}/", $post['category'])?>
            </span>
            <span class="tag">
                <i class="fa fa-tags"></i>
                <?php foreach ($post['tag'] as $index => $tag): ?>
                <?php $post['tag'][$index] = Helper::linkTo("{$blog['base']}tag/{$tag}/", $tag); ?>
                <?php endforeach; ?>
                <?=join($post['tag'], ', ')?>
            </span>
        </div>
        <div class="content"><?=$post['content']?></div>
    </article>
    <hr>
    <div id="paging">
        <span class="new">
            <?=isset($paging['p_url']) ? Helper::linkTo($paging['p_url'], "<< {$paging['p_title']}"): ''?>
        </span>
        <span class="old">
            <?=isset($paging['n_url']) ? Helper::linkTo($paging['n_url'], "{$paging['n_title']} >>"): ''?>
        </span>
        <span class="count">
            <?="{$paging['index']} / {$paging['total']}"?>
        </span>
    </div>
    <?php if (null !== $blog['disqus_shortname'] && $post['message']): ?>
    <hr>
    <div id="disqus_thread"></div>
    <?php endif; ?>
</div>