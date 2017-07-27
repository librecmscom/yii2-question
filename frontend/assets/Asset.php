<?php

namespace yuncms\question\frontend\assets;

use yii\web\AssetBundle;

/**
 * This declares the asset files required by QA.
 */
class Asset extends AssetBundle
{
    public $sourcePath = '@yuncms/question/frontend/views/assets';
    public $css = [
        'css/question.css',
    ];
    public $js = [
        'js/question.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}