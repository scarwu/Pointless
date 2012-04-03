<div class="tag">
<?php
foreach((array)$this->tag_list as $key => $value) {
	echo '<span><a href="' . BLOG_PATH . 'tag/' . $key . '">' . $key . '(' . count($value) . ')' . '</a></span>';
}
?>
</div>