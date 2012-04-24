<div class="archive">
	<div class="title">Archive</div>
	<div class="content">
		<?php
		foreach((array)$data as $key => $value) {
			echo '<span>' . link_to(BLOG_PATH .'archive/'. $key, $key.'('.count($value).')') . '</span>';
		}
		?>
	</div>
</div>