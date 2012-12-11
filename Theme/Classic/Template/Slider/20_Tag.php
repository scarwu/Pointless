<div class="tag">
	<div class="title"><?php echo linkTo(BLOG_PATH . 'tag', 'Tag'); ?></div>
	<div class="content">
		<?php
		foreach((array)$data as $key => $value)
			echo '<span>' . linkTo(BLOG_PATH .'tag/'. $key, $key.'('.count($value).')') . '</span>';
		?>
	</div>
</div>