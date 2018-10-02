<?php
use Oni\Web\Helper;

$baseUrl = $systemConfig['blog']['baseUrl'];
?>
<div id="side_category">
    <div class="title">
        <?=Helper::linkTo("{$baseUrl}category/", 'Category')?>
    </div>
    <div class="content">
        <?php foreach ($sideList['category'] as $key => $postList): ?>
        <?php $count = count($postList); ?>
        <span class="item">
            <?=Helper::linkTo("{$baseUrl}category/{$key}/", "{$key}({$count})")?>
        </span>
        <?php endforeach; ?>
    </div>
</div>
