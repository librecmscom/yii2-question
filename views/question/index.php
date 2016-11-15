<?php

use yii\helpers\Url;
use yii\helpers\Html;
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
    <div class="col-xs-12 col-md-9 main">
        <?= Nav::widget([
            'options' => ['class' => 'nav nav-tabs nav-tabs-zen mb10'],
            'items' => [
                ['label' => Yii::t('question', 'New question'), 'url' => ['/question/question/index'],
                    'active' => !isset($_GET['order']) ? true : ($_GET['order'] == 'new' ? true : false)],
                ['label' => Yii::t('question', 'Hot question'), 'url' => ['/question/question/index', 'order' => 'hottest']],
                ['label' => Yii::t('question', 'Reward question'), 'url' => ['/question/question/index', 'order' => 'reward']],
                ['label' => Yii::t('question', 'Unanswered question'), 'url' => ['/question/question/index', 'order' => 'unanswered']],
            ]
        ]); ?>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_item',//子视图
            'layout' => "{items}\n{pager}",
            'options' => ['class' => 'stream-list question-stream'],
            'itemOptions' => ['class' => 'stream-list-item']
        ]); ?>
    </div>
    <div class="col-xs-12 col-md-3 side">
        <?= Html::a(Yii::t('question', 'Ask a Question'), ['create'], ['class' => 'question-index-add-button btn btn-primary']); ?>

        <?= Popular::widget(); ?>

        <?= Tags::widget(); ?>

    </div>

</div>


