<div id="side_category">
    <div class="title"><?=linkTo("{$blog['base']}category/", 'Category')?></div>
    <div class="content">
        <?php foreach ($list as $key => $value): ?>
        <?php $count = count($value); ?>
        <span class="item"><?=linkTo("{$blog['base']}category/$key/", "$key($count)")?></span>
        <?php endforeach; ?>
    </div>
</div>
