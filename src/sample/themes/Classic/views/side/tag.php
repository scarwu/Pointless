<?php
use Oni\Web\Helper\HTML;

$baseUrl = $blog['config']['baseUrl'];
?>
<div id="side_tag">
    <div class="title">
        <?=HTML::linkTo("{$baseUrl}tag/", 'Tag')?>
    </div>
    <div class="content">
        <?php foreach ($sideList['tag'] as $key => $postList): ?>
        <?php $count = count($postList); ?>
        <span class="item">
            <?=HTML::linkTo("{$baseUrl}tag/{$key}/", "{$key}({$count})")?>
        </span>
        <?php endforeach; ?>
    </div>
</div>
