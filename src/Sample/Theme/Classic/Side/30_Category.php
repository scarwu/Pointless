<div class="category">
	<div class="title"><?=linkTo(BLOG_PATH . 'category', 'Category')?></div>
	<div class="content">
		<?php foreach((array)$data as $key => $value): ?>
		<span><?=linkTo(BLOG_PATH . "archive/$key", "$key(" . count($value) . ")")?></span>
		<?php endforeach; ?>
	</div>
</div>