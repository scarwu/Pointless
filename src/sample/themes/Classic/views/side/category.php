<?php
use Pointless\Library\Helper;
?>
<div id="side_category">
    <div class="title">
        <?=Helper::linkTo("{$blog['base']}category/", 'Category')?>
    </div>
    <div class="content">
        <?php foreach ($list as $key => $value): ?>
        <?php $count = count($value); ?>
        <span class="item">
            <?=Helper::linkTo("{$blog['base']}category/{$key}/", "{$key}({$count})")?>
        </span>
        <?php endforeach; ?>
    </div>
</div>
