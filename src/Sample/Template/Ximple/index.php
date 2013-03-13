
<!doctype html>
<html lang="<?=BLOG_LANG?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?=isset($data['title']) ? $data['title'] . ' | ' : NULL?><?=BLOG_NAME?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Load Cascading Style Sheets -->
	<link rel="stylesheet" href="<?=BLOG_PATH?>theme/main.css">
</head>
<body>
	<div id="nav">
		<a href="<?=BLOG_PATH?>">Home</a>
		<a href="<?=BLOG_PATH . 'about'?>">About</a>
		<a href="<?=BLOG_PATH . 'works'?>">Works</a>
	</div>
	<div id="slider">
		<a class="search" href="javascript:void(0);">
			<form action="http://www.google.com/search?q=as" target="_blank" method="get">
				<input type="hidden" name="q" value="site:<?=BLOG_DNS?>" />
				<input type="text" name="q" placeholder="Search" />
				<input type="submit" />
			</form>
		</a>
		<?=$data['slider'] ?>
		<a id="show" href="javascript:void(0);" class="arrow-right"></a>
		<a id="hide" href="javascript:void(0);" class="arrow-left"></a>
	</div>
	<div id="main">
		<div id="header">
			<h1><?=linkTo(BLOG_PATH, BLOG_NAME)?></h1>
			<hr>
			<h2><?=BLOG_SLOGAN?></h2>
		</div>
		<div id="container"><?=$data['container'] ?></div>
		<footer><?=BLOG_FOOTER?></footer>
	</div>
	
	<!-- Define and Load Javascript -->
	<script src="<?=BLOG_PATH?>theme/main.js"></script>
	<?php if(GOOGLE_ANALYSTIC): ?>
	<script>
		var _gaq = [['_setAccount', '<?=GOOGLE_ANALYSTIC?>'], ['_trackPageview']]; (function(d, t) {
			var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
			g.src = ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g, s)
		} (document, 'script'));
	</script>
	<?php endif; ?>
</body>
</html>
