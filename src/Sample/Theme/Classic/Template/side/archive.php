<div class="archive">
    <div class="title">
        <?=linkTo("{$blog['base']}archive", 'Archive')?>
    </div>
    <div class="content">
        <?php foreach ((array) $list as $key => $value): ?>
        <span>
            <?php $count = count($value); ?>
            <?=linkTo("{$blog['base']}archive/$key", "$key($count)")?>
        </span>
        <?php endforeach; ?>
    </div>
</div>
