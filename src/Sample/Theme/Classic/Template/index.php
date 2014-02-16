<!doctype html>
<html lang="<?=$data['config']['blog_lang']?>" class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="description" content="<?=$data['config']['blog_description']?>">
	<meta name="keywords" content="<?=$data['config']['blog_keywords']?><?=isset($data['keywords']) && '' != $data['keywords'] ? ",{$data['keywords']}" : ''?>">
	<title><?=isset($data['title']) ? $data['title'] . ' | ' : NULL?><?=$data['config']['blog_name']?></title>
	
	<!-- Load Cascading Style Sheets -->
	<link rel="stylesheet" href="<?=$data['config']['blog_base']?>theme/main.css">
</head>
<body>
	<div id="main">
		<div class="border">
			<header>
				<h1><?=linkTo($data['config']['blog_base'], $data['config']['blog_name'])?></h1>
				<h2><?=$data['config']['blog_slogan']?></h2>
			</header>
			<div id="nav">
				<form class="search" action="http://www.google.com/search?q=as" target="_blank" method="get">
					<input type="hidden" name="q" value="site:<?=$data['config']['blog_dn']?>" />
					<input type="text" name="q" placeholder="Search" />
					<input type="submit" />
				</form>
				<a href="<?=$data['config']['blog_base']?>">Home</a>
				<a href="<?="{$data['config']['blog_base']}about"?>">About</a>
			</div>
			<div id="container"><?=$data['block']['container']?></div>
			<div id="side"><?=$data['block']['side']?></div>
			<footer><?=$data['config']['blog_footer']?></footer>
		</div>
	</div>
	<!-- Define and Load Javascript -->
	<script src="<?=$data['config']['blog_base']?>theme/main.js"></script>
	<?php if($data['config']['google_analytics']): ?>
	<script>
		var _gaq = [['_setAccount', '<?=$data['config']['google_analytics']?>'], ['_trackPageview']]; (function(d, t) {
			var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
			g.src = ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g, s)
		} (document, 'script'));
	</script>
	<?php endif; ?>
</body>
</html>