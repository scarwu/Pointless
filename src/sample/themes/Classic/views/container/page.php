<?php
use Pointless\Library\Helper;
?>
<div id="container_page">
    <?php foreach ($post['list'] as $article): ?>
    <article class="post_block">
        <h1 class="title">
            <?=Helper::linkTo("{$blog['base']}article/{$article['url']}", $article['title'])?>
        </h1>
        <div class="info">
            <?php if (null !== $blog['disqus_shortname'] && $article['message']): ?>
            <span class="comments">
                <i class="fa fa-comment"></i>
                <a href="<?=Helper::linkEncode("{$blog['base']}article/{$article['url']}/")?>#disqus_thread">0 Comment</a>
            </span>
            <?php endif; ?>
            <span class="date">
                <i class="fa fa-calendar"></i>
                <?=Helper::linkTo("{$blog['base']}archive/{$article['year']}/", $article['date'])?>
            </span>
            <span class="category">
                <i class="fa fa-folder-open"></i>
                <?=Helper::linkTo("{$blog['base']}category/{$article['category']}/", $article['category'])?>
            </span>
            <span class="tag">
                <i class="fa fa-tags"></i>
                <?php foreach ($article['tag'] as $index => $tag): ?>
                <?php $article['tag'][$index] = Helper::linkTo("{$blog['base']}tag/{$tag}/", $tag); ?>
                <?php endforeach; ?>
                <?=join($article['tag'], ', ')?>
            </span>
        </div>
        <div class="content"><?=$article['summary']?></div>
        <a class="more" href="<?="{$blog['base']}article/{$article['url']}"?>">Read more</a>
    </article>
    <hr>
    <?php endforeach; ?>
    <div id="paging">
        <span class="new">
            <?=isset($paging['p_url']) ? Helper::linkTo($paging['p_url'], '<< Newer Posts'): ''?>
        </span>
        <span class="old">
            <?=isset($paging['n_url']) ? Helper::linkTo($paging['n_url'], 'Older Posts >>'): ''?>
        </span>
        <span class="count">
            <?="{$paging['index']} / {$paging['total']}"?>
        </span>
    </div>
</div>