<?php
use Pointless\Library\Helper;
?>
<div id="side_archive">
    <div class="title">
        <?=Helper::linkTo("{$blog['base']}archive/", 'Archive')?>
    </div>
    <div class="content">
        <?php foreach ($list as $key => $value): ?>
        <?php $count = count($value); ?>
        <span class="item">
            <?=Helper::linkTo("{$blog['base']}archive/$key/", "$key($count)")?>
        </span>
        <?php endforeach; ?>
    </div>
</div>