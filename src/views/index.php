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

$title = isset($container['title'])
    ? "{$container['title']} | {$blog['config']['name']}"
    : $blog['config']['name'];
$description = (!isset($container['description']) || '' === $container['description'])
    ? $blog['config']['description']
    : $container['description'];
?>
<!doctype html>
<html lang="<?=$lang?>">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="author" content="<?=$blog['config']['author']?>" />
    <meta property="og:description" name="description" content="<?=$description?>" />
    <meta property="og:title" content="<?=$title?>" />
    <meta property="og:url" content="<?=$protocol?>://<?="{$domainName}{$baseUrl}{$container['url']}"?>" />
    <meta property="og:image" content="<?=$protocol?>://<?="{$domainName}{$baseUrl}"?>images/icon.jpg" />
    <meta property="og:type" content="website" />

    <title><?=$title?></title>

    <link rel="canonical" href="<?=$protocol?>://<?="{$domainName}{$baseUrl}{$container['url']}"?>" />
    <link rel="image_src" href="<?=$protocol?>://<?="{$domainName}{$baseUrl}"?>images/icon.jpg" />
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