<!doctype html>
<html lang="<?=$blog['lang']?>" class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="<?=$blog['description']?>">
    <meta name="keywords" content="<?=$blog['keywords']?>">
    <title><?=$blog['title']?></title>

    <link rel="stylesheet" href="<?=$blog['base']?>theme/main.css">
</head>
<body>
    <div id="main">
        <div class="border">
            <header>
                <h1><?=linkTo($blog['base'], $blog['name'])?></h1>
                <h2><?=$blog['slogan']?></h2>
            </header>
            <div id="nav">
                <form class="search" action="http://www.google.com/search?q=as" target="_blank" method="get">
                    <input type="hidden" name="q" value="site:<?=$blog['dn']?>" />
                    <input type="text" name="q" placeholder="Search" />
                    <input type="submit" />
                </form>
                <a href="<?=$blog['base']?>">Home</a>
                <a href="<?="{$blog['base']}about"?>">About</a>
            </div>
            <div id="container"><?=$block['container']?></div>
            <div id="side"><?=$block['side']?></div>
            <footer><?=$blog['footer']?></footer>
        </div>
    </div>

    <script src="<?=$blog['base']?>theme/main.js"></script>
    <?php if (null !== $blog['google_analytics']): ?>
    <script>
        var _gaq = [['_setAccount', '<?=$blog['google_analytics']?>'], ['_trackPageview']]; (function (d, t) {
            var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
            g.src = ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g, s)
        } (document, 'script'));
    </script>
    <?php endif; ?>
</body>
</html>
