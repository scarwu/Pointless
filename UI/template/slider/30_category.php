<div class="category">
	<div class="title">Category</div>
	<div class="content">
		<?php
		foreach((array)$this->category_list as $key => $value) {
			echo '<span><a href="' . BLOG_PATH . 'category/' . $key . '">' . $key . ' (' . count($value) . ')' . '</a></span>';
		}
		?>
	</div>
</div>