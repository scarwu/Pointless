<div class="category">
	<div class="title"><?=linkTo("{$data['config']['blog_base']}category", 'Category')?></div>
	<div class="content">
		<?php foreach((array)$data['list'] as $key => $value): ?>
		<span><?=linkTo("{$data['config']['blog_base']}category/$key", "$key(" . count($value) . ")")?></span>
		<?php endforeach; ?>
	</div>
</div>