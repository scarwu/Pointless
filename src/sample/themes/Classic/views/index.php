<?php
use Pointless\Library\Helper;
?>
<!doctype html>
<html lang="<?=$blog['lang']?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="<?=$blog['description']?>">

    <title><?=$blog['title']?></title>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=$blog['base']?>assets/styles.css">
</head>
<body>
    <div id="main">
        <div id="border">
            <hgroup id="header">
                <h1><?=Helper::linkTo($blog['base'], $blog['name'])?></h1>
                <h2><?=$blog['slogan']?></h2>
            </hgroup>
            <nav id="nav">
                <form id="nav_search" action="http://www.google.com/search?q=as" target="_blank" method="get">
                    <input type="hidden" name="q" value="site:<?=$blog['dn']?>">
                    <input type="text" name="q" placeholder="Search">
                    <input type="submit">
                </form>
                <a href="<?=$blog['base']?>">Home</a>
                <a href="<?="{$blog['base']}about/"?>">About</a>
            </nav>
            <div id="container"><?=$block['container']?></div>
            <div id="side"><?=$block['side']?></div>
            <footer id="footer">
                <?=$blog['footer']?> - <a href="https://github.com/scarwu/Pointless" target="_blank">Powered by Pointless</a>
            </footer>
        </div>
    </div>

    <script>
        function asyncLoad(src) {
            var s = document.createElement('script');
            s.src = src; s.async = true;
            var e = document.getElementsByTagName('script')[0];
            e.parentNode.insertBefore(s, e);
        }

        <?php if(null != $blog['google_analytics']): ?>
        var _gaq = [['_setAccount', '<?=$blog['google_analytics']?>'], ['_trackPageview']];
        asyncLoad(('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js');
        <?php endif; ?>

        <?php if(null != $blog['disqus_shortname']): ?>
        var disqus_shortname = '<?=$blog['disqus_shortname']?>';
        if (document.getElementById('disqus_comments')) {
            asyncLoad('http://' + disqus_shortname + '.disqus.com/count.js');
        }
        if (document.getElementById('disqus_thread')) {
            asyncLoad('http://' + disqus_shortname + '.disqus.com/embed.js');
        }
        <?php endif; ?>
    </script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.1/modernizr.min.js" async></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.0/highlight.min.js"></script>
    <script src="<?=$blog['base']?>assets/scripts.js"></script>
</body>
</html>