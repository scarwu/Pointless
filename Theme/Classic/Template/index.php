<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="<?php echo BLOG_LANG; ?>"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="<?php echo BLOG_LANG; ?>"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="<?php echo BLOG_LANG; ?>"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html lang="<?php echo BLOG_LANG; ?>" class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo isset($data['title']) ? $data['title'] . ' | ' : NULL; ?><?php echo BLOG_NAME; ?></title>
	<meta name="viewport" content="width=640, initial-scale=1">
	
	<!-- Load Cascading Style Sheets -->
	<link rel="stylesheet" href="<?php echo BLOG_PATH; ?>theme/main.css">
</head>
<body>
	<div id="main">
		<header>
			<hgroup>
				<h1><?php echo linkTo(BLOG_PATH, BLOG_NAME); ?></h1>
				<h2><?php echo BLOG_SLOGAN; ?></h2>
			</hgroup>
		</header>
		<div id="nav">
			<a href="<?php echo BLOG_PATH; ?>">Home</a>
			<a href="<?php echo BLOG_PATH . 'about'; ?>">About</a>
			<a href="<?php echo BLOG_PATH . 'works'; ?>">Works</a>
			<a class="search" href="javascript:void(0);">Search
				<form action="http://www.google.com/search?q=as" target="_blank" method="get">
					<input type="hidden" name="q" value="site:<?php echo BLOG_DNS; ?>" />
					<input type="text" name="q" />
					<input type="submit" />
				</form>
			</a>
		</div>
		<div id="container"><?php echo $data['container'] ?></div>
		<div id="slider"><?php echo $data['slider'] ?></div>
		<footer><?php echo BLOG_FOOTER; ?></footer>
	</div>
	<!-- Define and Load Javascript -->
	<script src="<?php echo BLOG_PATH; ?>theme/main.js"></script>
	<?php if(GOOGLE_ANALYSTIC): ?>
	<script>
		var _gaq = [['_setAccount', '<?php echo GOOGLE_ANALYSTIC; ?>'], ['_trackPageview']]; (function(d, t) {
			var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
			g.src = ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g, s)
		} (document, 'script'));
	</script>
	<?php endif; ?>
</body>
</html>