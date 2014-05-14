<div id="container_archive">
    <h1 class="title"><?=$post['title']?></h1>
    <?php foreach ((array) $post['list'] as $year => $month_list): ?>
    <div class="year_archive">
        <?php foreach ((array) $month_list as $month => $article_list): ?>
        <div class="month_archive">
            <div class="list">
                <?php foreach ((array) $article_list as $article): ?>
                <article class="post_block">
                    <h1 class="title">
                        <?=linkTo("{$blog['base']}article/{$article['url']}", $article['title'])?>
                    </h1>
                    <div class="info">
                        <span class="archive">
                            <i class="fa fa-calendar"></i>
                            <?=linkTo("{$blog['base']}archive/{$article['year']}/", $article['date'])?>
                        </span>
                        <span class="category">
                            <i class="fa fa-folder-open"></i>
                            <?=linkTo("{$blog['base']}category/{$article['category']}/", $article['category'])?>
                        </span>
                        <span class="tag">
                            <i class="fa fa-tags"></i>
                            <?php foreach ((array) $article['tag'] as $index => $tag): ?>
                            <?php $article['tag'][$index] = linkTo("{$blog['base']}tag/$tag/", $tag); ?>
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
            <?=isset($paging['p_url']) ? linkTo($paging['p_url'], "<< {$paging['p_title']}"): ''?>
        </span>
        <span class="old">
            <?=isset($paging['n_url']) ? linkTo($paging['n_url'], "{$paging['n_title']} >>"): ''?>
        </span>
        <span class="count">
            <?="{$paging['index']} / {$paging['total']}"?>
        </span>
    </div>
</div>