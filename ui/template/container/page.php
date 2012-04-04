<div id="page">
	<?php
	foreach((array)$data['article_list'] as $index => $article_info) {
		$md = file_get_contents(ARTICLES . $article_info['number'] . SEPARATOR . 'article.md');
		echo '<article>';
		echo '<div class="title"><a href="' . BLOG_PATH . 'article/' . $article_info['number'] . '">' . $article_info['title'] . '</a></div>';
		echo '<div class="info">';
		echo '<span class="date">Date: ' . $article_info['post_date'] . '</span>';
		echo '</div>';
		echo '<div class="content">' . preg_replace('/<!--more-->(.|\n)*/', '', Markdown($md)) . '</div>';
		echo '<a class="more" href="' . BLOG_PATH . 'article/' . $article_info['number'] . '">Read more ...</a>';
		echo '</article>';
		echo '<hr>';
	}
	?>
	<div class="bar"><?php echo $data['bar'] ?></div>
</div>