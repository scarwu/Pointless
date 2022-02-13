<?php
use Oni\Web\Helper\HTML;

$domainName = $blog['config']['domainName'];
$baseUrl = $blog['config']['baseUrl'];
$disqusShortname = $blog['config']['disqusShortname'];

// Paging
$paging = $container['paging'];
$prevButton = isset($paging['prevUrl'])
    ? HTML::linkTo("{$baseUrl}{$paging['prevUrl']}", "<< {$paging['prevTitle']}") : '';
$nextButton = isset($paging['nextUrl'])
    ? HTML::linkTo("{$baseUrl}{$paging['nextUrl']}", "{$paging['nextTitle']} >>") : '';
$indicator = "{$paging['currentIndex']} / {$paging['totalIndex']}";
?>
<div id="container_article">
    <article class="post_block">
        <h1 class="title"><?=$container['title']?></h1>
        <div class="info">
            <?php if (null !== $disqusShortname && $container['withMessage']): ?>
            <span class="comment">
                <i class="fa fa-comment"></i>
                <a href="<?=HTML::linkEncode("{$baseUrl}{$container['url']}/")?>#disqus_thread">0 Comment</a>
            </span>
            <?php endif; ?>
            <span class="date">
                <i class="fa fa-calendar"></i>
                <?=HTML::linkTo("{$baseUrl}archive/{$container['year']}/", $container['date'])?>
            </span>
            <span class="category">
                <i class="fa fa-folder-open"></i>
                <?=HTML::linkTo("{$baseUrl}category/{$container['category']}/", $container['category'])?>
            </span>
            <span class="tag">
                <i class="fa fa-tags"></i>
                <?=join(', ', array_map(function ($tag) use ($baseUrl) {
                    return HTML::linkTo("{$baseUrl}tag/{$tag}/", $tag);
                }, $container['tags']))?>
            </span>
        </div>
        <div class="content"><?=$container['content']?></div>
    </article>
    <hr>
    <div id="paging">
        <span class="new"><?=$prevButton?></span>
        <span class="old"><?=$nextButton?></span>
        <span class="count"><?=$indicator?></span>
    </div>
    <?php if (null !== $disqusShortname && $container['withMessage']): ?>
    <hr>
    <div id="disqus_thread"></div>
    <?php endif; ?>
</div>