<?php

/** @var yii\web\View $this */
/** @var array $items */

use yii\helpers\Html;

?>
<div class="timeline">
    <?php foreach ($items as $index => $item): ?>
        <?php
        $itemIndex = $index + 1;
        $animationDelay = $itemIndex * 0.1;
        $highlightClass = !empty($item['highlight']) && $item['highlight'] ? 'highlight' : '';
        ?>
        <div class="timeline-item" style="animation-delay: <?= $animationDelay ?>s;">
            <div class="time">
                <?= Html::encode($item['time'] ?? '') ?>
            </div>
            <div class="activity <?= $highlightClass ?>">
                <?= !empty($item['icon']) ? $item['icon'] . ' ' : '' ?>
                <?= Html::encode($item['activity'] ?? '') ?>
                <?php if (!empty($item['clue'])): ?>
                    <div class="clue">
                        📍 Clue: <?= Html::encode($item['clue']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

