<?php

namespace yuncms\question;

use Yii;

/**
 * This is the main module class for the QA module.
 *
 * To use QA, include it as a module in the application configuration like the following:
 *
 * ~~~
 * return [
 *     ......
 *     'modules' => [
 *         'question' => ['class' => 'yuncms\question\Module'],
 *     ],
 * ]
 * ~~~
 *
 * With the above configuration, you will be able to access QA Module in your browser using
 * the URL `http://localhost/path/to/index.php?r=question`
 *
 * You can then access QA via URL: `http://localhost/path/to/index.php/question`
 */
class Module extends \yii\base\Module
{

    public $defaultRoute = 'question';

    /**
     * 初始化
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * 注册语言包
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['question*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => __DIR__ . '/messages',
        ];
    }
}
