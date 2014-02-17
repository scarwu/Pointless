<div class="category">
	<div class="title">
		<?=linkTo("{$data['config']['blog_base']}category", 'Category')?>
	</div>
	<div class="content">
		<?php foreach((array)$data['list'] as $key => $value): ?>
		<span>
			<?php $count = count($value); ?>
			<?=linkTo("{$data['config']['blog_base']}category/$key", "$key($count)")?>
		</span>
		<?php endforeach; ?>
	</div>
</div>