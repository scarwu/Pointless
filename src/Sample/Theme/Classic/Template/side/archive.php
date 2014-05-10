<div id="side_archive">
    <div class="title"><?=linkTo("{$blog['base']}archive", 'Archive')?></div>
    <div class="content">
        <?php foreach ((array) $list as $key => $value): ?>
        <?php $count = count($value); ?>
        <span class="item"><?=linkTo("{$blog['base']}archive/$key", "$key($count)")?></span>
        <?php endforeach; ?>
    </div>
</div>