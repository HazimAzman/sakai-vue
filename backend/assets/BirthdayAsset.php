<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Birthday page asset bundle
 * 
 * Manages CSS and JavaScript assets for the birthday celebration page
 * 
 * @package app\assets
 */
class BirthdayAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
        'css/birthday.css',
    ];
    
    public $js = [
        // Add custom JS files here if needed
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
