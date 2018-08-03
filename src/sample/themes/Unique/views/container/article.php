<?php
use Pointless\Library\Helper;
?>
<div id="container_article">
    <article class="post_block">
        <h1 class="title"><?=$post['title']?></h1>
        <div class="info">
            <div class="date">
                <i class="fa fa-calendar"></i>
                <?=Helper::linkTo("{$blog['base']}archive/{$post['year']}/", $post['date'])?>
            </div>
            <?php foreach($post['tag'] as $index =>  $tag): ?>
            <div class="tag">
                <i class="fa fa-tag"></i>
                <?=Helper::linkTo("{$blog['base']}tag/$tag/", $tag)?>
            </div>
            <?php endforeach; ?>
            <?php if(null != $blog['disqus_shortname'] && $post['message']): ?>
            <div class="disqus_comments">
                <i class="fa fa-comment"></i>
                <a href="<?=Helper::linkEncode("{$blog['base']}{$post['url']}")?>#disqus_thread">0 Comment</a>
            </div>
            <?php endif; ?>
            <hr>
            <div class="social_tool">
                <div class="twitter">
                    <a class="twitter-share-button" data-url="http://<?=Helper::linkEncode("{$blog['url']}{$post['url']}")?>" data-text="<?=$blog['title']?>" data-lang="en" data-via="xneriscool"></a>
                </div>
                <div class="facebook">
                    <div class="fb-like" data-href="http://<?=Helper::linkEncode("{$blog['url']}{$post['url']}")?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
                </div>
                <div class="google">
                    <div class="g-plusone" data-href="http://<?=Helper::linkEncode("{$blog['url']}{$post['url']}")?>" data-size="medium"></div>
                </div>
            </div>
        </div>
        <div class="content"><?=$post['content']?></div>
    </article>
    <?php if(null != $blog['disqus_shortname'] && $post['message']): ?>
    <div id="disqus_thread"></div>
    <?php endif; ?>
    <div id="paging">
        <span class="new">
            <?=isset($paging['p_url'])
                ? Helper::linkTo($paging['p_url'], "<< {$paging['p_title']}"): ''?>
        </span>
        <span class="old">
            <?=isset($paging['n_url'])
                ? Helper::linkTo($paging['n_url'], "{$paging['n_title']} >>"): ''?>
        </span>
        <span class="count">
            <?="{$paging['index']} / {$paging['total']}"?>
        </span>
    </div>
</div>
<div id="fb-root"></div>