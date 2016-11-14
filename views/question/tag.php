<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\widgets\ListView;
use yuncms\question\Asset;

/** @var \yii\data\ActiveDataProvider $dataProvider */
Asset::register($this);
$this->title = Yii::t('question', 'Tag') . ' ' . $tag;
$this->params['breadcrumbs'][] = Yii::t('question', 'Questions');
$this->params['breadcrumbs'][] = Yii::t('question', 'Tag');
$this->params['breadcrumbs'][] = $tag;
?>
<div class="row">
    <div class="col-md-9">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_item',//子视图
            'layout' => "{items}\n{pager}",
            'options' => ['class' => 'question-list list-group'],
            'itemOptions' => ['class' => 'list-group-item clearfix question-item']
        ]); ?>
    </div>
    <div class="col-md-3">
        <?= Html::a(Yii::t('question', 'Ask a Question'), ['create'], ['class' => 'question-index-add-button btn btn-primary']); ?>

        <?= \yuncms\question\widgets\Popular::widget(); ?>

        <?= \yuncms\question\widgets\Tags::widget(); ?>

    </div>

</div>


