<div id="article">
    <article>
        <div class="title"><?=$post['title']?></div>
        <div class="info">
            <?php if (null !== $blog['disqus_shortname']): ?>
            <span class="comment">
                <a href="<?=linkEncode("{$blog['base']}{$post['url']}/")?>#disqus_thread">
                    0 Comment
                </a>
            </span>
            <br>
            <?php endif; ?>
            <span class="date"><?=$post['date']?></span>
            -
            <span class="category">
                Category:
                <?=linkTo("{$blog['base']}category/{$post['category']}", $post['category'])?>
            </span>
            -
            <span class="tag">
                Tag:
                <?php foreach ((array) $post['tag'] as $index => $tag): ?>
                <?php $post['tag'][$index] = linkTo("{$blog['base']}tag/$tag", $tag); ?>
                <?php endforeach; ?>
                <?=join($post['tag'], ', ')?>
            </span>
        </div>
        <div class="content">
            <?=$post['content']?>
        </div>
    </article>
    <hr>
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
    <?php if (null !== $blog['disqus_shortname']): ?>
    <hr>
    <div id="disqus_thread"></div>
    <?php endif; ?>
</div>
