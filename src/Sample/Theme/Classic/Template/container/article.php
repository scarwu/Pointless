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
            <br />
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
            <?=isset($post['bar']['p_url'])
                ? linkTo($post['bar']['p_url'], "<< {$post['bar']['p_title']}"): ''?>
        </span>
        <span class="old">
            <?=isset($post['bar']['n_url'])
                ? linkTo($post['bar']['n_url'], "{$post['bar']['n_title']} >>"): ''?>
        </span>
        <span class="count">
            <?="{$post['bar']['index']} / {$post['bar']['total']}"?>
        </span>
    </div>
    <?php if (null !== $blog['disqus_shortname']): ?>
    <hr>
    <div id="disqus_thread"></div>
    <script type="text/javascript">
        var disqus_shortname = '<?=$blog['disqus_shortname']?>';
        (function () {
            var embed = document.createElement('script');
            embed.type = 'text/javascript';
            embed.async = true;
            embed.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(embed);

            var count = document.createElement('script');
            count.async = true;
            count.type = 'text/javascript';
            count.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(count);
        }());
    </script>
    <?php endif; ?>
</div>
