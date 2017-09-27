<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model yuncms\question\models\Question */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('question', 'Manage Question'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 question-view">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('question', 'Manage Question'),
                            'url' => ['index'],
                        ],
                        [
                            'label' => Yii::t('question', 'Delete Question'),
                            'url' => ['delete', 'id' => $model->id],
                            'options' => [
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => Yii::t('question', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ],
                            ]
                        ],
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'user.nickname',
                    'title',
                    'alias',
                    'price',
                    'hide:boolean',
                    'content:ntext',
                    'answers',
                    'views',
                    'followers',
                    'collections',
                    'comments',
                    'status',
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>


            <?= ListView::widget([
                'options' => ['class' => null],
                'itemOptions' => ['class' => 'media'],
                'dataProvider' => $dataProvider,
                'itemView' => '_answer_item',//子视图
                'layout' => "{items}\n{pager}",
                'viewParams' => ['question' => $model],
            ]); ?>

            <?php Box::end(); ?>
        </div>
    </div>
</div>

