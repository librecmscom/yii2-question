<?php

use yuncms\question\frontend\assets\Asset;

Asset::register($this);
$this->title = Yii::t('question', 'Ask a Question');
$this->params['breadcrumbs'][] = ['label' => Yii::t('question', 'Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <h1><?= $this->title ?></h1>
        
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>
