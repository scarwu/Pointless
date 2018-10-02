<?php
use Oni\Web\Helper;

$baseUrl = $systemConfig['blog']['baseUrl'];
?>
<div id="side_archive">
    <div class="title">
        <?=Helper::linkTo("{$baseUrl}archive/", 'Archive')?>
    </div>
    <div class="content">
        <?php foreach ($sideList['archive'] as $key => $postList): ?>
        <?php $count = count($postList); ?>
        <span class="item">
            <?=Helper::linkTo("{$baseUrl}archive/{$key}/", "{$key}({$count})")?>
        </span>
        <?php endforeach; ?>
    </div>
</div>