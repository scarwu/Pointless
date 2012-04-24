<div class="tag">
	<div class="title">Tag</div>
	<div class="content">
		<?php
		foreach((array)$this->tag_list as $key => $value) {
			echo '<span><a href="' . BLOG_PATH . 'tag/' . $key . '">' . $key . ' (' . count($value) . ')' . '</a></span>';
		}
		?>
	</div>
</div>