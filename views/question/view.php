<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yuncms\question\models\Vote;
use yuncms\question\Asset;

/**
 * @var yii\web\View $this
 * @var yuncms\question\models\Question $model
 */
Asset::register($this);
$this->title = Html::encode($model->title);
//$this->params['breadcrumbs'][] = ['label' => Yii::t('question', 'Questions'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;

$answerOrders = [
    'Active' => 'active',
    'Oldest' => 'oldest',
    'Votes' => 'votes'
];
?>
<div class="row mt-10">
    <div class="col-xs-12 col-md-9 main">
        <div class="widget-question">
            <h4 class="title"><?= Html::encode($this->title) ?></h4>
            <ul class="taglist-inline">
                <?php foreach ($model->tags as $tag): ?>
                    <li class="tagPopup">
                        <a class="tag"
                           href="<?= Url::to(['/question/question/tag', 'tag' => $tag->name]) ?>"><?= Html::encode($tag->name) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="description mt-10">
                <div class="text-fmt ">
                    <?= $model->body ?>
                </div>
                <div class="post-opt mt-10">
                    <ul class="list-inline">
                        <?php if ($model->isAuthor()) : ?>
                            <li><a href="<?= Url::to(['update', 'id' => $model->id]) ?>" class="edit"
                                   data-toggle="tooltip" data-placement="right" title=""
                                   data-original-title="补充细节，以得到更准确的答案"><i
                                        class="fa fa-edit"></i> <?= Yii::t('question', 'Edit'); ?></a></li>
                            <li><a href="<?= Url::to(['delete', 'id' => $model->id]) ?>" class="edit" data-method="post"
                                   data-confirm="<?= Yii::t('question', 'Sure?'); ?>"><i
                                        class="fa fa-remove"></i> <?= Yii::t('question', 'Remove'); ?></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- 回答开始 -->
        <div class="widget-answers mt-15">
            <div class="btn-group pull-right" role="group">
                <?php foreach ($answerOrders as $aId => $aOrder): ?>
                    <a class="btn btn-default btn-xs <?= ($aOrder == $answerOrder) ? 'active' : '' ?>"
                       href="<?= Url::to(['view', 'id' => $model->id, 'alias' => $model->alias, 'answers' => $aOrder]) ?>">
                        <?= Yii::t('question', $aId) ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <h2 class="h4 post-title"><?= Yii::t('question', '{n, plural, =0{No answers yet} =1{One answer} other{# answers}}', ['n' => $dataProvider->totalCount]); ?></h2>
            <div class="text-center">
                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_answer_item',//子视图
                    'layout' => "{items}\n{pager}",
                    'viewParams' => ['question' => $model],
                ]); ?>
            </div>
        </div>
        <div class="widget-answer-form mt-15">
            <?= \yuncms\question\widgets\Answer::widget(['questionId' => $model->id]); ?>
        </div>
    </div>
    <div class="col-xs-12 col-md-3 side">
        <div class="widget-box">
            <ul class="widget-action list-unstyled">
                <!-- 关注部分 -->
                <li>
                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isFollowed($model, $model->id)): ?>
                        <button type="button" data-target="follow-button" class="btn mr-10 btn-success active"
                                data-source_type="question" data-source_id="<?= $model->id ?>" data-show_num="true"
                                data-toggle="tooltip" data-placement="right" title="" data-original-title="关注后将获得更新提醒">
                            已关注
                        </button>
                    <?php else: ?>
                        <button type="button" data-target="follow-button" class="btn mr-10 btn-success"
                                data-source_type="question" data-source_id="<?= $model->id ?>" data-show_num="true"
                                data-toggle="tooltip" data-placement="right" title="" data-original-title="关注后将获得更新提醒">
                            关注
                        </button>
                    <?php endif; ?>
                    <strong id="follower-num"><?= $model->followers ?></strong> 关注
                </li>
                <!-- 收藏部分 -->
                <li>
                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isCollected($model, $model->id)): ?>
                        <button type="button" data-target="collect-button" class="btn mr-10 btn-success active"
                                data-source_type="question" data-source_id="<?= $model->id ?>" data-show_num="true"
                                data-toggle="tooltip" data-placement="right" title="" data-original-title="关注后将获得更新提醒">
                            已收藏
                        </button>
                    <?php else: ?>
                        <button type="button" data-target="collect-button" class="btn mr-10 btn-success"
                                data-source_type="question" data-source_id="<?= $model->id ?>" data-show_num="true"
                                data-toggle="tooltip" data-placement="right" title="" data-original-title="关注后将获得更新提醒">
                            收藏
                        </button>
                    <?php endif; ?>
                    <strong id="collection-num"><?= $model->collections ?></strong> 收藏，<strong
                        class="no-stress"><?= $model->views ?></strong> 浏览
                </li>
                <li>
                    <i class="fa fa-clock-o"></i>
                    <a href="<?= Url::to(['/user/profile/show', 'id' => $model->user_id]) ?>"
                       target="_blank"><?= Html::encode($model->user->username) ?></a>
                    提出于 <?= Yii::$app->formatter->asRelativeTime($model->updated_at); ?></li>
            </ul>
        </div>
        <div class="widget-box">
            <h2 class="h4 widget-box__title">相似问题</h2>
            <ul class="widget-links list-unstyled list-text">
            </ul>
        </div>
    </div>
</div>
