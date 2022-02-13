<?php
use Oni\Web\Helper\HTML;

$baseUrl = $blog['config']['baseUrl'];
?>
<div id="side_archive">
    <div class="title">
        <?=HTML::linkTo("{$baseUrl}archive/", 'Archive')?>
    </div>
    <div class="content">
        <?php foreach ($sideList['archive'] as $key => $postList): ?>
        <?php $count = count($postList); ?>
        <span class="item">
            <?=HTML::linkTo("{$baseUrl}archive/{$key}/", "{$key}({$count})")?>
        </span>
        <?php endforeach; ?>
    </div>
</div>