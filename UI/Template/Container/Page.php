<div id="page">
	<?php
	foreach((array)$data['article_list'] as $index => $article_info) {
		echo '<article>';
		echo '<div class="title">' . link_to(BLOG_PATH.'article/'.$article_info['url'], $article_info['title']) . '</div>';
		echo '<div class="info">';
		echo '<span class="date">Date: ' . $article_info['date'] . '</span>';
		echo '</div>';
		echo '<div class="content">' . preg_replace('/<!--more-->(.|\n)*/', '', $article_info['content']) . '</div>';
		echo '<a class="more" href="' . BLOG_PATH . 'article/' . $article_info['url'] . '">Read more ...</a>';
		echo '</article>';
		echo '<hr>';
	}
	
	$bar = sprintf('<span class="count">< %d / %d ></span>', $data['bar'][0], $data['bar'][1]);
	if($data['bar'][1] != 1)
		if($data['bar'][0] == 1)
			$bar .= sprintf('<a class="new"></a><a class="old" href="/page/%d">Older Posts >></a>', $data['bar'][0]+1);
		elseif($data['bar'][0] == $data['bar'][1])
			$bar .= sprintf('<a class="new" href="/page/%d"><< Newer Posts</a><a class="old"></a>', $data['bar'][0]-1);
		else {
			$bar .= sprintf('<a class="new" href="/page/%d"><< Newer Posts</a>', $data['bar'][0]-1);
			$bar .= sprintf('<a class="old" href="/page/%d">Older Posts >></a>', $data['bar'][0]+1);
		}
	?>
	<div class="bar"><?php echo $bar; ?></div>
</div>