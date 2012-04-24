<div class="tag">
	<div class="title">Tag</div>
	<div class="content">
		<?php
		foreach((array)$this->tag_list as $key => $value) {
			echo '<span>' . link_to(BLOG_PATH .'tag/'. $key, $key.'('.count($value).')') . '</span>';
		}
		?>
	</div>
</div>