<div id="page">
	<?php
	foreach((array)$data['article_list'] as $index => $article_info) {
		$md = file_get_contents(ARTICLES . $article_info['number'] . SEPARATOR . 'article.md');
		echo '<article>';
		echo '<div class="title"><a href="' . BLOG_PATH . 'article/' . ($index+1) . '">' . $article_info['title'] . '</a></div>';
		echo '<div class="content">' . preg_replace('/<!-- more -->(.|\n)*/', '', Markdown($md)) . '</div>';
		echo '<a class="more" href="' . BLOG_PATH . 'article/' . ($index+1) . '">Read more</a>';
		echo '</article>';
		echo '<hr>';
	}
	?>
	<div class="bar"><?php echo $data['bar'] ?></div>
</div>