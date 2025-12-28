<?php

/** @var yii\web\View $this */
/** @var app\models\Birthday $model */

use app\assets\BirthdayAsset;
use app\widgets\BirthdayTimeline;
use yii\helpers\Html;
use yii\helpers\Url;

BirthdayAsset::register($this);

$this->title = 'Happy Birthday ' . Html::encode($model->name) . '! 🎉';
$this->params['breadcrumbs'] = [];
?>

<div class="birthday-container">
    <div class="birthday-header">
        <div class="header-content">
            <div class="birthday-girl">
                🎂 <?= Html::encode($model->name) ?> 🎂
            </div>
            <div class="birthday-message">
                <?= Html::encode($model->message) ?> 🎉✨
            </div>
        </div>
    </div>

    <div class="image-placeholder" id="mainImage">
        <?php if ($model->hasImage()): ?>
            <?= Html::img(
                $model->imagePath,
                [
                    'alt' => 'Birthday Girl - ' . Html::encode($model->name),
                    'id' => 'birthdayImage',
                    'class' => 'loaded',
                ]
            ) ?>
            <span style="display: none;">📸 Insert Birthday Girl's Image Here</span>
        <?php else: ?>
            <span>
                📸 Insert Birthday Girl's Image Here
                <br>
                <small style="font-size: 0.8em; margin-top: 10px; display: block;">
                    Place images in: <?= Html::encode(Yii::getAlias('@webroot/images/birthday/')) ?>
                    <br>
                    (Supported: mimi.jpg, mimi.png, birthday-girl.jpg, birthday-girl.png)
                </small>
            </span>
            <?= Html::img('', [
                'alt' => 'Birthday Girl',
                'id' => 'birthdayImage',
                'style' => 'display: none;',
            ]) ?>
        <?php endif; ?>
    </div>

    <div class="schedule-section">
        <h2 class="section-title">🎊 Celebration Schedule 🎊</h2>
        
        <?= BirthdayTimeline::widget([
            'items' => $model->getScheduleItems(),
        ]) ?>
    </div>

    <div class="footer">
        <div class="footer-message">
            May your special day be as amazing as you are! 🌟
        </div>
        <div class="emoji">🎈🎉🎂🎁✨</div>
    </div>
</div>

<?php
// Register JavaScript using Yii's registerJs method
$this->registerJs("
    // Function to load image when user inserts it
    function loadImage(imagePath) {
        var img = document.getElementById('birthdayImage');
        var placeholder = document.getElementById('mainImage');
        var span = placeholder ? placeholder.querySelector('span') : null;
        
        if (img && imagePath) {
            img.src = imagePath;
            img.onload = function() {
                img.classList.add('loaded');
                img.style.display = 'block';
                if (span) span.style.display = 'none';
            };
            img.onerror = function() {
                console.error('Image could not be loaded. Please check the path.');
            };
        }
    }

    // Add some interactive sparkle effect on click
    document.addEventListener('click', function(e) {
        var sparkle = document.createElement('div');
        sparkle.style.position = 'fixed';
        sparkle.style.left = e.clientX + 'px';
        sparkle.style.top = e.clientY + 'px';
        sparkle.style.width = '6px';
        sparkle.style.height = '6px';
        sparkle.style.background = '#f5576c';
        sparkle.style.borderRadius = '50%';
        sparkle.style.pointerEvents = 'none';
        sparkle.style.zIndex = '9999';
        sparkle.style.animation = 'confetti-fall 1s forwards';
        document.body.appendChild(sparkle);
        
        setTimeout(function() {
            if (sparkle && sparkle.parentNode) {
                sparkle.parentNode.removeChild(sparkle);
            }
        }, 1000);
    });
", \yii\web\View::POS_READY);
?>
