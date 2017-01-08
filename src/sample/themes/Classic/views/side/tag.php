<?php
use Pointless\Library\Helper;
?>
<div id="side_tag">
    <div class="title">
        <?=Helper::linkTo("{$blog['base']}tag/", 'Tag')?>
    </div>
    <div class="content">
        <?php foreach ($list as $key => $value): ?>
        <?php $count = count($value); ?>
        <span class="item">
            <?=Helper::linkTo("{$blog['base']}tag/{$key}/", "{$key}({$count})")?>
        </span>
        <?php endforeach; ?>
    </div>
</div>
