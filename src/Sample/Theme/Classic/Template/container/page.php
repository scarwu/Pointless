<div id="page">
    <?php foreach ((array) $post['list'] as $article): ?>
    <article>
        <div class="title">
            <?=linkTo("{$blog['base']}article/{$article['url']}", $article['title'])?>
        </div>
        <div class="info">
            <?php if (null !== $blog['disqus_shortname']): ?>
            <span class="comments">
                <a href="<?=linkEncode("{$blog['base']}article/{$article['url']}/")?>#disqus_thread">0 Comment</a>
            </span>
            <br />
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
    <div class="bar">
        <span class="new">
            <?=isset($post['bar']['p_url'])
                ? linkTo($post['bar']['p_url'], '<< Newer Posts'): ''?>
        </span>
        <span class="old">
            <?=isset($post['bar']['n_url'])
                ? linkTo($post['bar']['n_url'], 'Older Posts >>'): ''?>
        </span>
        <span class="count">
            <?="{$post['bar']['index']} / {$post['bar']['total']}"?>
        </span>
    </div>
</div>
<?php if (null !== $blog['disqus_shortname']): ?>
<script type="text/javascript">
    var disqus_shortname = '<?=$blog['disqus_shortname']?>';
    (function () {
        var count = document.createElement('script');
        count.async = true;
        count.type = 'text/javascript';
        count.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(count);
    }());
</script>
<?php endif; ?>
