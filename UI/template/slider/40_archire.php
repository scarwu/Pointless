<div class="archive">
	<div class="title">Archive</div>
	<div class="content">
		<?php
		foreach((array)$this->archive_list as $key => $value) {
			echo '<span><a href="' . BLOG_PATH . 'archive/' . $key . '">' . $key . ' (' . count($value) . ')' . '</a></span>';
		}
		?>
	</div>
</div>