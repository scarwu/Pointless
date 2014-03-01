<div class="category">
    <div class="title">
        <?=linkTo("{$blog['base']}category", 'Category')?>
    </div>
    <div class="content">
        <?php foreach ((array) $list as $key => $value): ?>
        <span>
            <?php $count = count($value); ?>
            <?=linkTo("{$blog['base']}category/$key", "$key($count)")?>
        </span>
        <?php endforeach; ?>
    </div>
</div>
