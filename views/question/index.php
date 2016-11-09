<?php

use yii\helper\Url;
use yii\helper\Html;
use yii\bootstrap\Nav;
use yii\widgets\ListView;
use yuncms\question\Asset;
use yuncms\question\widgets\Popular;
use yuncms\question\widgets\Tags;

/** @var \yii\data\ActiveDataProvider $dataProvider */
Asset::register($this);
$this->title = Yii::t('question', 'Questions');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-md-9">
        <?= Nav::widget([
            'options' => ['class' => 'nav nav-tabs nav-question'],
            'items' => [
                ['label' => Yii::t('question', 'New question'), 'url' => ['/question/question/index'],
                    'active' => !isset($_GET['order']) ? true : ($_GET['order'] == 'new' ? true : false)],
                ['label' => Yii::t('question', 'Hot question'), 'url' => ['/question/question/index', 'order' => 'hot']],
                ['label' => Yii::t('question', 'Unanswered question'), 'url' => ['/question/question/index', 'order' => 'unanswered']],
            ]
        ]); ?>
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

        <?= Popular::widget(); ?>

        <?= Tags::widget(); ?>

    </div>

</div>


