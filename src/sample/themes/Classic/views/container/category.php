<?php
use Pointless\Library\Helper;
?>
<div id="container_category">
    <h1 class="title"><?=$post['title']?></h1>
    <?php foreach ($post['list'] as $year => $month_list): ?>
    <div class="year_archive">
        <?php foreach ($month_list as $month => $article_list): ?>
        <div class="month_archive">
            <div class="list">
                <?php foreach ($article_list as $article): ?>
                <article class="post_block">
                    <h1 class="title">
                        <?=Helper::linkTo("{$blog['base']}article/{$article['url']}", $article['title'])?>
                    </h1>
                    <div class="info">
                        <span class="archive">
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
                </article>
                <?php endforeach; ?>
            </div>
        </div>
        <hr>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
    <div id="paging">
        <span class="new">
            <?=isset($paging['p_url']) ? Helper::linkTo($paging['p_url'], "<< {$paging['p_title']}") : ''?>
        </span>
        <span class="old">
            <?=isset($paging['n_url']) ? Helper::linkTo($paging['n_url'], "{$paging['n_title']} >>") : ''?>
        </span>
        <span class="count">
            <?="{$paging['index']} / {$paging['total']}"?>
        </span>
    </div>
</div>