<?php
use Oni\Web\Helper;

$baseUrl = $systemConfig['blog']['baseUrl'];
?>
<div id="side_tag">
    <div class="title">
        <?=Helper::linkTo("{$baseUrl}tag/", 'Tag')?>
    </div>
    <div class="content">
        <?php foreach ($sideList['tag'] as $key => $postList): ?>
        <?php $count = count($postList); ?>
        <span class="item">
            <?=Helper::linkTo("{$baseUrl}tag/{$key}/", "{$key}({$count})")?>
        </span>
        <?php endforeach; ?>
    </div>
</div>
