<?php

namespace yuncms\question;

use yii\web\AssetBundle;

/**
 * This declares the asset files required by QA.
 */
class Asset extends AssetBundle
{
    public $sourcePath = '@yuncms/yii2-question/assets';
    public $css = [
        'css/question.css',
    ];
    public $js = [
        'js/question.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}