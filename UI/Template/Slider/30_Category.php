<div class="category">
	<div class="title">Category</div>
	<div class="content">
		<?php
		foreach((array)$data as $key => $value) {
			echo '<span>' . link_to(BLOG_PATH.'category/'.$key, $key.'('.count($value).')') . '</span>';
		}
		?>
	</div>
</div>