<!DOCTYPE html>
<html lang="zh-tw" class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo isset($blog['title']) ? $blog['title'] . ' | ' : NULL; ?><?php echo BLOG_NAME; ?></title>
	<meta name="viewport" content="width=640, initial-scale=1">
	
	<!-- Load Cascading Style Sheets -->
	<link rel="stylesheet" href="<?php echo BLOG_PATH; ?>main.css">
</head>
<body>
	<div id="main">
		<header>
			<hgroup>
				<h1><a href="<?php echo BLOG_PATH; ?>"><?php echo BLOG_NAME; ?></a></h1>
				<h2><?php echo BLOG_SLOGAN; ?></h2>
			</hgroup>
		</header>
		<div id="nav"><a href="<?php echo BLOG_PATH; ?>">Home</a></div>
		<div id="container"><?php echo $blog['container'] ?></div>
		<div id="slider"><?php echo $blog['slider'] ?></div>
		<footer><?php echo BLOG_FOOTER; ?></footer>
	</div>
	<!-- Define and Load Javascript -->
	<script src="<?php echo BLOG_PATH; ?>main.js"></script>
	<?php if(GOOGLE_ANALISTIC): ?>
	<script>
		var _gaq = [['_setAccount', '<?php echo GOOGLE_ANALISTIC; ?>'], ['_trackPageview']]; (function(d, t) {
			var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
			g.src = ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g, s)
		} (document, 'script'));
	</script>
	<?php endif; ?>
</body>
</html>