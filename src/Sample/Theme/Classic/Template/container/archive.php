<div id="archive">
    <div class="title"><?=$post['title']?></div>
    <?php foreach ((array) $post['list'] as $year => $month_list): ?>
    <div class="year_archive">
        <div class="year"><?=$year?></div>
        <?php foreach ((array) $month_list as $month => $article_list): ?>
        <div class="month_archive">
            <div class="month"><?=$month?></div>
            <div class="list">
                <?php foreach ((array) $article_list as $article): ?>
                <article>
                    <span class="title">
                        <?=linkTo("{$blog['base']}article/{$article['url']}", $article['title'])?>
                    </span>
                    <span class="category">
                        Category:
                        <?=linkTo("{$blog['base']}category/{$article['category']}", $article['category'])?>
                    </span>
                    <span class="tag">
                        Tag:
                        <?php foreach ((array) $article['tag'] as $index => $tag): ?>
                        <?php $article['tag'][$index] = linkTo("{$blog['base']}tag/$tag", $tag); ?>
                        <?php endforeach; ?>
                        <?=join($article['tag'], ', ')?>
                    </span>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
        <hr>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
    <div class="bar">
        <span class="new">
            <?=isset($paging['p_url'])
                ? linkTo($paging['p_url'], "<< {$paging['p_title']}"): ''?>
        </span>
        <span class="old">
            <?=isset($paging['n_url'])
                ? linkTo($paging['n_url'], "{$paging['n_title']} >>"): ''?>
        </span>
        <span class="count">
            <?="{$paging['index']} / {$paging['total']}"?>
        </span>
    </div>
</div>
