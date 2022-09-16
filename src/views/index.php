<?php
$postfix = time();
$lang = $blog['config']['lang'];
$slogan = $blog['config']['slogan'];
$footer = $blog['config']['footer'];

$protocol = $blog['config']['withSSL'] ? 'https' : 'http';
$domainName = $blog['config']['domainName'];
$baseUrl = $blog['config']['baseUrl'];

$googleAnalytics = $blog['config']['googleAnalytics'];
$disqusShortname = $blog['config']['disqusShortname'];

$title = (true === isset($container['title']))
    ? "{$container['title']} | {$blog['config']['name']}"
    : $blog['config']['name'];
$description = (false === isset($container['description']) || '' === $container['description'])
    ? $blog['config']['description']
    : $container['description'];
$url = "{$protocol}://{$domainName}{$baseUrl}{$container['url']}";
$coverImage = (false === isset($container['coverImage']))
    ? "{$protocol}://{$domainName}{$baseUrl}images/icon.jpg"
    : $container['coverImage'];
?>
<!doctype html>
<html lang="<?=$lang?>">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="author" content="<?=$blog['config']['author']?>" />
    <meta property="og:description" name="description" content="<?=$description?>" />
    <meta property="og:title" content="<?=$title?>" />
    <meta property="og:url" content="<?=$url?>" />
    <meta property="og:image" content="<?=$coverImage?>" />
    <meta property="og:type" content="website" />

    <title><?=$title?></title>

    <link rel="canonical" href="<?=$url?>" />
    <link rel="image_src" href="<?=$coverImage?>" />
    <link rel="shortcut icon" href="<?=$protocol?>://<?="{$domainName}{$baseUrl}"?>favicon.ico" />
    <?php foreach ($theme['constant']['assets']['styles'] as $file): ?>
    <link rel="stylesheet" href="<?=$baseUrl?><?=$file?>?<?=$postfix?>" />
    <?php endforeach; ?>
    <?php foreach ($theme['constant']['assets']['scripts'] as $file): ?>
    <script src="<?=$baseUrl?><?=$file?>?<?=$postfix?>" async></script>
    <?php endforeach; ?>
    <script>
        window._nx = {
            googleAnalytics: <?=(null !== $googleAnalytics) ? "'{$googleAnalytics}'" : 'undefined'?>,
            disqusShortname: <?=(null !== $disqusShortname) ? "'{$disqusShortname}'" : 'undefined'?>
        };
    </script>
</head>
<body>
    <?=$this->loadLayout()?>
</body>
</html>