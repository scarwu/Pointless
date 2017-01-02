<div id="side_tag">
    <div class="title"><?=linkTo("{$blog['base']}tag/", 'Tag')?></div>
    <div class="content">
        <?php foreach ($list as $key => $value): ?>
        <?php $count = count($value); ?>
        <span class="item"><?=linkTo("{$blog['base']}tag/$key/", "$key($count)")?></span>
        <?php endforeach; ?>
    </div>
</div>
