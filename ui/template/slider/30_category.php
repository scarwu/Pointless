<div class="tag">
<?php
foreach((array)$this->category_list as $key => $value) {
	echo '<span><a href="' . BLOG_PATH . 'category/' . $key . '">' . $key . '(' . count($value) . ')' . '</a></span>';
}
?>
</div>