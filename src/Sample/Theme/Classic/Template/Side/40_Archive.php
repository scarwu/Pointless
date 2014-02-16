<div class="archive">
	<div class="title"><?=linkTo("{$data['config']['blog_base']}archive", 'Archive')?></div>
	<div class="content">
		<?php foreach((array)$data['list'] as $key => $value): ?>
		<span><?=linkTo("{$data['config']['blog_base']}archive/$key", "$key(" . count($value) . ")")?></span>
		<?php endforeach; ?>
	</div>
</div>