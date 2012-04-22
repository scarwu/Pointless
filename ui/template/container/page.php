<div id="page">
	<?php
	foreach((array)$data['article_list'] as $index => $article_info) {
		echo '<article>';
		echo '<div class="title"><a href="' . BLOG_PATH . 'article/' . $article_info['url'] . '">' . $article_info['title'] . '</a></div>';
		echo '<div class="info">';
		echo '<span class="date">Date: ' . $article_info['date'] . '</span>';
		echo '</div>';
		echo '<div class="content">' . preg_replace('/<!--more-->(.|\n)*/', '', $article_info['content']) . '</div>';
		echo '<a class="more" href="' . BLOG_PATH . 'article/' . $article_info['url'] . '">Read more ...</a>';
		echo '</article>';
		echo '<hr>';
	}
	?>
	<div class="bar"><?php echo $data['bar']; ?></div>
</div>