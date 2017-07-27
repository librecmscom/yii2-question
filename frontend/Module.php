<?php

namespace yuncms\question\frontend;

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
class Module extends \yuncms\question\Module
{

    public $defaultRoute = 'question';


}
