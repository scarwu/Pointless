<div class="archive">
	<div class="title"><?php echo linkTo(BLOG_PATH . 'archive', 'Archive'); ?></div>
	<div class="content">
		<?php
		foreach((array)$data as $key => $value)
			echo '<span>' . linkTo(BLOG_PATH . 'archive/'. $key, $key.'('.count($value).')') . '</span>';
		?>
	</div>
</div>