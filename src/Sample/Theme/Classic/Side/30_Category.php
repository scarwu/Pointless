<div class="category">
	<div class="title"><?=linkTo(BLOG_PATH . 'category', 'Category')?></div>
	<div class="content">
		<?php
		foreach((array)$data as $key => $value)
			echo '<span>' . linkTo(BLOG_PATH . "archive/$key", sprintf("$key(%s)", count($value))) . '</span>';
		?>
	</div>
</div>