<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\widgets\ListView;
use yuncms\question\frontend\assets\Asset;
use yuncms\question\widgets\Tags;
use yuncms\question\widgets\Popular;
use yuncms\question\models\Question;

/** @var \yii\data\ActiveDataProvider $dataProvider */
Asset::register($this);
$this->title = Yii::t('question', 'Questions');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12 col-md-9 main">

        <section class="tag-header mt-20">
            <div>
                <span class="h4 tag-header-title"><?=Html::encode($model->name)?></span>

                <div class="tag-header-follow">
                    <?php
                    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->hasTagValues($model->id)) {
                        ?>
                        <button type="button" data-target="follow-tag" class="btn btn-default btn-xs active"
                                data-source_id="<?= $model->id ?>" data-show_num="false" data-toggle="tooltip"
                                data-placement="right" title="" data-original-title="关注后将获得更新提醒">已关注
                        </button>
                        <?php
                    } else {
                        ?>
                        <button type="button" data-target="follow-tag" class="btn btn-default btn-xs"
                                data-source_id="<?= $model->id ?>" data-show_num="false" data-toggle="tooltip"
                                data-placement="right" title="" data-original-title="关注后将获得更新提醒">关注
                        </button>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <p class="tag-header-summary"><?= empty($model->description) ? Yii::t('question', 'No introduction') : Html::encode($model->description); ?></p>
        </section>
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
        <div class="tab-content">

            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_item',//子视图
                'layout' => "{items}\n{pager}",
                'options' => ['class' => 'question-list question-stream'],
                'itemOptions' => ['class' => 'question-list-item', 'tag' => 'section']
            ]); ?>
            <!-- /.stream-list -->
        </div>
    </div><!-- /.main -->
    <div class="col-xs-12 col-md-3 side">
        <div class="side-alert alert alert-warning">
            <p>智慧求问，智慧回答。集思广益，让全世界的人都来帮助你。</p>
            <?= Html::a(Yii::t('question', 'Ask a Question'), ['create'], ['class' => 'btn btn-primary btn-block mt-10']); ?>
        </div>

        <?= Tags::widget() ?>

        <div class="widget-box mt30">
            <h2 class="widget-box-title">
                回答榜<a href="<?= Url::to(['/question/top/answers']) ?>" title="更多">»</a>
            </h2>
            <ol class="widget-top10">
                <?php
                $topAnswerUsers = \yuncms\user\models\Extend::top('answers', 8);
                ?>
                <?php foreach ($topAnswerUsers as $index => $topAnswerUser): ?>
                    <li class="text-muted">
                        <img class="avatar-32"
                             src="<?= $topAnswerUser->user->getAvatar('big') ?>">
                        <a href="<?= Url::to(['/user/space/view', 'id' => $topAnswerUser->user_id]) ?>"
                           class="ellipsis"><?= $topAnswerUser->user->nickname ?></a>
                        <span class="text-muted pull-right"><?= $topAnswerUser->answers ?> 回答</span>
                    </li>
                <?php endforeach; ?>

            </ol>
        </div>

    </div>
</div>