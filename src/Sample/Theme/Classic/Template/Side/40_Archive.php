<div class="archive">
	<div class="title">
		<?=linkTo("{$data['config']['blog_base']}archive", 'Archive')?>
	</div>
	<div class="content">
		<?php foreach((array)$data['list'] as $key => $value): ?>
		<span>
			<?php $count = count($value); ?>
			<?=linkTo("{$data['config']['blog_base']}archive/$key", "$key($count)")?>
		</span>
		<?php endforeach; ?>
	</div>
</div>