<div class="tag">
	<div class="title">
		<?=linkTo("{$data['blog']['base']}tag", 'Tag')?>
	</div>
	<div class="content">
		<?php foreach((array)$data['list'] as $key => $value): ?>
		<span>
			<?php $count = count($value); ?>
			<?=linkTo("{$data['blog']['base']}tag/$key", "$key($count)")?>
		</span>
		<?php endforeach; ?>
	</div>
</div>