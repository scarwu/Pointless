<!DOCTYPE html>
<html lang="zh-tw" class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo isset($output_data['title']) ? $output_data['title'] . ' | ' : NULL; ?><?php echo BLOG_NAME; ?></title>
	<meta name="viewport" content="width=640, initial-scale=1">
	
	<!-- Load Cascading Style Sheets -->
	<link rel="stylesheet" href="<?php echo BLOG_PATH; ?>main.css">
</head>
<body>
	<div id="main">
		<header>
			<h1><?php echo BLOG_NAME; ?></h1>
			<h2><?php echo BLOG_SLOGAN; ?></h2>
		</header>
		<div class="container"><?php echo $output_data['container'] ?></div>
		<div id="slider"><?php echo $output_data['slider'] ?></div>
		<footer><?php echo BLOG_FOOTER; ?></footer>
	</div>
	<!-- Define and Load Javascript -->
	<script src="<?php echo BLOG_PATH; ?>main.js"></script>
</body>
</html>