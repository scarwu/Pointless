<!doctype html>
<html lang="<?=$data['lang']?>" class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="description" content="<?=$data['description']?>">
	<meta name="keywords" content="<?=$data['keywords']?>">
	<title><?=$data['name']?></title>
	
	<!-- Load Cascading Style Sheets -->
	<link rel="stylesheet" href="<?=$data['base']?>theme/main.css">
</head>
<body>
	<div id="main">
		<div class="border">
			<header>
				<h1><?=linkTo($data['base'], $data['name'])?></h1>
				<h2><?=$data['slogan']?></h2>
			</header>
			<div id="nav">
				<form class="search" action="http://www.google.com/search?q=as" target="_blank" method="get">
					<input type="hidden" name="q" value="site:<?=$data['dn']?>" />
					<input type="text" name="q" placeholder="Search" />
					<input type="submit" />
				</form>
				<a href="<?=$data['base']?>">Home</a>
				<a href="<?="{$data['base']}about"?>">About</a>
			</div>
			<div id="container"><?=$data['block']['container']?></div>
			<div id="side"><?=$data['block']['side']?></div>
			<footer><?=$data['footer']?></footer>
		</div>
	</div>
	<!-- Define and Load Javascript -->
	<script src="<?=$data['base']?>theme/main.js"></script>
	<?php if($data['google_analytics']): ?>
	<script>
		var _gaq = [['_setAccount', '<?=$data['google_analytics']?>'], ['_trackPageview']]; (function(d, t) {
			var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
			g.src = ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g, s)
		} (document, 'script'));
	</script>
	<?php endif; ?>
</body>
</html>