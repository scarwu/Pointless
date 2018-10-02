<?php
use Oni\Web\Helper;

$name = $systemConfig['blog']['name'];
$lang = $systemConfig['blog']['lang'];
$slogan = $systemConfig['blog']['slogan'];
$footer = $systemConfig['blog']['footer'];

$domainName = $systemConfig['blog']['domainName'];
$baseUrl = $systemConfig['blog']['baseUrl'];

$googleAnalytics = $systemConfig['blog']['googleAnalytics'];
$disqusShortname = $systemConfig['blog']['disqusShortname'];

$title = isset($container['title'])
    ? "{$container['title']} | {$systemConfig['blog']['name']}"
    : $systemConfig['blog']['name'];
$description = (!isset($container['description']) || '' === $container['description'])
    ? $systemConfig['blog']['description']
    : $container['description'];
?>
<!doctype html>
<html class="no-js" style="display: block !important;" lang="<?=$lang?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="<?=$description?>">

    <title><?=$title?></title>

    <link rel="stylesheet" href="<?=$baseUrl?>assets/styles/theme.min.css">

    <script src="<?=$baseUrl?>assets/scripts/vendor/modernizr.min.js"></script>
    <script src="<?=$baseUrl?>assets/scripts/theme.min.js" async></script>

    <script>
        function asyncLoad(src) {
            var s = document.createElement('script');
            s.src = src; s.async = true;
            var e = document.getElementsByTagName('script')[0];
            e.parentNode.insertBefore(s, e);
        }
    </script>
    <?php if(null !== $googleAnalytics): ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', '<?=$googleAnalytics?>', 'auto');
        ga('send', 'pageview');
    </script>
    <?php endif; ?>
</head>
<body>
    <div id="main">
        <div id="border">
            <hgroup id="header">
                <h1><?=Helper::linkTo($baseUrl, $name)?></h1>
                <h2><?=$slogan?></h2>
            </hgroup>

            <nav id="nav">
                <form id="nav_search" action="http://www.google.com/search?q=as" target="_blank" method="get">
                    <input type="hidden" name="q" value="site:<?=$domainName?>">
                    <input type="text" name="q" placeholder="Search">
                    <input type="submit">
                </form>
                <a href="<?=$baseUrl?>">Home</a>
                <a href="<?="{$baseUrl}about/"?>">About</a>
            </nav>

            <div id="container">
                <?=$this->loadContent()?>
            </div>

            <div id="side">
            <?php foreach ($themeConfig['views']['side'] as $name): ?>
            <?=$this->loadPartial("side/{$name}")?>
            <?php endforeach; ?>
            </div>

            <footer id="footer">
                <?=$footer?> - <a href="https://github.com/scarwu/Pointless" target="_blank">Powered by Pointless</a>
            </footer>
        </div>
    </div>

    <?php if(null !== $disqusShortname): ?>
    <script>
        var disqusShortname = '<?=$disqusShortname?>';

        if (document.getElementsByTagName('disqus_comments')) {
            asyncLoad('//' + disqusShortname + '.disqus.com/count.js');
        }

        if (document.getElementById('disqus_thread')) {
            asyncLoad('//' + disqusShortname + '.disqus.com/embed.js');
        }
    </script>
    <?php endif; ?>
</body>
</html>