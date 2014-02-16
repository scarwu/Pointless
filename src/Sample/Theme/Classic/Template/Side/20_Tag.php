<div class="tag">
	<div class="title"><?=linkTo("{$data['config']['blog_base']}tag", 'Tag')?></div>
	<div class="content">
		<?php foreach((array)$data['list'] as $key => $value): ?>
		<span><?=linkTo("{$data['config']['blog_base']}tag/$key", "$key(" . count($value) . ")")?></span>
		<?php endforeach; ?>
	</div>
</div>