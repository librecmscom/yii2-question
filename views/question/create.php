<?php
/**
 * Leaps Framework [ WE CAN DO IT JUST THINK IT ]
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2015 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

use Leaps\Question\Asset;

Asset::register($this);
$this->title = Leaps::t('question', 'Ask a Question');
$this->params['breadcrumbs'][] = ['label' => Leaps::t('question', 'Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <h1><?= $this->title ?></h1>
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>
