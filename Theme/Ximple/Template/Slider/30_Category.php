<div class="category">
	<div class="title"><?php echo linkTo(BLOG_PATH . 'category', 'Category'); ?></div>
	<div class="content">
		<?php
		foreach((array)$data as $key => $value)
			echo '<span>' . linkTo(BLOG_PATH . 'category/' . $key, $key . '(' . count($value) . ')') . '</span>';
		?>
	</div>
</div>