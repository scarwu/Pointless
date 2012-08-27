<div class="tag">
	<div class="title"><?php echo link_to(BLOG_PATH . 'tag', 'Tag'); ?></div>
	<div class="content">
		<?php
		foreach((array)$data as $key => $value)
			echo '<span>' . link_to(BLOG_PATH .'tag/'. $key, $key.'('.count($value).')') . '</span>';
		?>
	</div>
</div>