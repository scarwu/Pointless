<?php
use Oni\Web\Helper;

$domainName = $systemConfig['blog']['domainName'];
$baseUrl = $systemConfig['blog']['baseUrl'];
$disqusShortname = $systemConfig['blog']['disqusShortname'];

// Paging
$paging = $container['paging'];
$prevButton = isset($paging['prevUrl'])
    ? Helper::linkTo("{$baseUrl}{$paging['prevUrl']}", '<< Newer Posts') : '';
$nextButton = isset($paging['nextUrl'])
    ? Helper::linkTo("{$baseUrl}{$paging['nextUrl']}", 'Older Posts >>') : '';
$indicator = "{$paging['currentIndex']} / {$paging['totalIndex']}";
?>
<div id="container_page">
    <?php foreach ($container['list'] as $article): ?>
    <article class="post_block">
        <h1 class="title">
            <?=Helper::linkTo("{$baseUrl}article/{$article['url']}", $article['title'])?>
        </h1>
        <div class="info">
            <?php if (null !== $disqusShortname && $article['withMessage']): ?>
            <span class="comments">
                <i class="fa fa-comment"></i>
                <a href="<?=Helper::linkEncode("{$baseUrl}article/{$article['url']}/")?>#disqus_thread">0 Comment</a>
            </span>
            <?php endif; ?>
            <span class="date">
                <i class="fa fa-calendar"></i>
                <?=Helper::linkTo("{$baseUrl}archive/{$article['year']}/", $article['date'])?>
            </span>
            <span class="category">
                <i class="fa fa-folder-open"></i>
                <?=Helper::linkTo("{$baseUrl}category/{$article['category']}/", $article['category'])?>
            </span>
            <span class="tag">
                <i class="fa fa-tags"></i>
                <?php foreach ($article['tags'] as $index => $tag): ?>
                <?php $article['tag'][$index] = Helper::linkTo("{$baseUrl}tag/{$tag}/", $tag); ?>
                <?php endforeach; ?>
                <?=join($article['tag'], ', ')?>
            </span>
        </div>
        <div class="content"><?=$article['summary']?></div>
        <a class="more" href="<?="{$baseUrl}article/{$article['url']}"?>">More</a>
    </article>
    <hr>
    <?php endforeach; ?>
    <div id="paging">
        <span class="new"><?=$prevButton?></span>
        <span class="old"><?=$nextButton?></span>
        <span class="count"><?=$indicator?></span>
    </div>
</div>