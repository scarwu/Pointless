<?php
use Pointless\Library\Helper;
?>
<div id="side_tag">
    <div class="title">
        <?=Helper::linkTo("{$blog['base']}tag/", 'Tag')?>
    </div>
    <div class="content">
        <?php foreach((array)$list as $key => $value): ?>
        <span class="item">
            <?php $count = count($value); ?>
            <?=Helper::linkTo("{$blog['base']}tag/$key/", "$key($count)")?>
        </span>
        <?php endforeach; ?>
    </div>
</div>