<div class="archive">
	<div class="title"><?php echo link_to(BLOG_PATH . 'archive', 'Archive'); ?></div>
	<div class="content">
		<?php
		foreach((array)$data as $key => $value)
			echo '<span>' . link_to(BLOG_PATH .'archive/'. $key, $key.'('.count($value).')') . '</span>';
		?>
	</div>
</div>