<!DOCTYPE html>
<html lang="zh-tw" class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $html_info['title']; ?> | <?php echo BLOG_NAME; ?></title>
	<meta name="viewport" content="width=640, initial-scale=1">
	
	<!-- Load Cascading Style Sheets -->
	<link rel="stylesheet" href="<?php echo BLOG_PATH; ?>main.css">
</head>
<body>
	<header>
		<h1><?php echo BLOG_NAME; ?></h1>
		<h2><?php echo BLOG_SLOGAN; ?></h2>
	</header>
	<div id="main">
		<span class="title">
			<?php echo $html_info['title']; ?>
		</span>
		<div class="content">
			<?php echo $html_info['content']; ?>
		</div>
	</div>
	<div id="slider"></div>
	<footer><?php echo BLOG_FOOTER; ?></footer>
	
	<!-- Define and Load Javascript -->
	<script src="<?php echo BLOG_PATH; ?>main.js"></script>
</body>
</html>