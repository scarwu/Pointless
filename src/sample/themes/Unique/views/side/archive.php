<?php
use Pointless\Library\Helper;
?>
<div id="side_archive">
    <div class="title">
        <?=Helper::linkTo("{$blog['base']}archive/", 'Archive')?>
    </div>
    <div class="content">
        <?php foreach((array)$list as $key => $value): ?>
        <span class="item">
            <?php $count = count($value); ?>
            <?=Helper::linkTo("{$blog['base']}archive/$key/", "$key($count)")?>
        </span>
        <?php endforeach; ?>
    </div>
</div>