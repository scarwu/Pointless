<div id="container_page">
    <?php foreach ((array) $post['list'] as $article): ?>
    <article class="post_block">
        <div class="title">
            <?=linkTo("{$blog['base']}article/{$article['url']}", $article['title'])?>
        </div>
        <div class="info">
            <?php if (null !== $blog['disqus_shortname']): ?>
            <span class="comments">
                <a href="<?=linkEncode("{$blog['base']}article/{$article['url']}/")?>#disqus_thread">0 Comment</a>
            </span>
            <br>
            <?php endif; ?>
            <span class="date"><?=$article['date']?></span>
            -
            <span class="category">
                Category: <?=linkTo("{$blog['base']}category/{$article['category']}", $article['category'])?>
            </span>
            -
            <span class="tag">
                Tag:
                <?php foreach ((array) $article['tag'] as $index => $tag): ?>
                <?php $article['tag'][$index] = linkTo("{$blog['base']}tag/$tag", $tag); ?>
                <?php endforeach; ?>
                <?=join($article['tag'], ', ')?>
            </span>
        </div>
        <div class="content">
            <?=preg_replace('/<!--more-->(.|\n)*/', '', $article['content'])?>
        </div>
        <a class="more" href="<?="{$blog['base']}article/{$article['url']}"?>">
            Read more
        </a>
    </article>
    <hr>
    <?php endforeach; ?>
    <div id="paging">
        <span class="new">
            <?=isset($paging['p_url'])
                ? linkTo($paging['p_url'], '<< Newer Posts'): ''?>
        </span>
        <span class="old">
            <?=isset($paging['n_url'])
                ? linkTo($paging['n_url'], 'Older Posts >>'): ''?>
        </span>
        <span class="count">
            <?="{$paging['index']} / {$paging['total']}"?>
        </span>
    </div>
</div>