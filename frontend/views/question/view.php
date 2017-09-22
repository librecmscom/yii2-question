<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap\Modal;
use yii\helpers\HtmlPurifier;
use yii\bootstrap\ActiveForm;
use yuncms\question\models\Answer;
use yuncms\question\models\Question;
use yuncms\question\frontend\assets\Asset;

/**
 * @var yii\web\View $this
 * @var yuncms\question\models\Question $model
 */
Asset::register($this);
$this->title = Html::encode($model->title);
?>
<div class="row mt-10">
    <div class="col-xs-12 col-md-9 main">
        <div class="widget-question">
            <h4 class="title" style="font-size: 18px;">
                <?php if ($model->price > 0): ?>
                    <span class="text-gold">
                        <i class="fa fa-database"></i> <?= $model->price ?>
                    </span>
                <?php endif; ?>
                <?= Html::encode($this->title) ?></h4>
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
                    <?= HTMLPurifier::process($model->content); ?>
                </div>
                <div class="post-opt mt-10">
                    <ul class="list-inline">
                        <li><a class="comments" data-toggle="collapse" href="#comments-question-<?= $model->id ?>"
                               aria-expanded="false" aria-controls="comment-<?= $model->id ?>">
                                <i
                                    class="fa fa-comment-o"></i>
                                <?= Yii::t('question', '{n, plural, =0{No comment} =1{One comment} other{# reviews}}', ['n' => $model->comments]); ?>
                            </a></li>

                        <?php if ($model->isAuthor()) : ?>
                            <li><a href="<?= Url::to(['update', 'id' => $model->id]) ?>" class="edit"
                                   data-toggle="tooltip" data-placement="right" title=""
                                   data-original-title="<?= Yii::t('question', 'Add details to get a more accurate answer.') ?>"><i
                                        class="fa fa-edit"></i> <?= Yii::t('question', 'Edit'); ?></a></li>
                            <li><a href="<?= Url::to(['delete', 'id' => $model->id]) ?>" class="edit" data-method="post"
                                   data-confirm="<?= Yii::t('question', 'Sure?'); ?>"><i
                                        class="fa fa-remove"></i> <?= Yii::t('question', 'Remove'); ?></a></li>
                            <li><a href="#" data-toggle="modal" data-target="#appendReward"><i
                                        class="fa fa-database"></i> 追加悬赏</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <?= \yuncms\comment\frontend\widgets\Comment::widget(['source_type' => 'question', 'source_id' => $model->id, 'hide_cancel' => false]) ?>

                <!-- 分享
                <div class="mb-10">
                    这里是分享代码
                </div>-->
            </div>

            <!-- 最佳答案 -->
            <?php if ($model->status === Question::STATUS_END && $bestAnswer): ?>
                <div class="best-answer mt-10">
                    <div class="trophy-title">
                        <h3>
                            <i class="fa fa-trophy"></i> <?= Yii::t('question', 'Best answer') ?>
                            <span
                                class="pull-right text-muted adopt_time"><?= Yii::$app->formatter->asRelativeTime($bestAnswer->created_at); ?></span>
                        </h3>
                    </div>
                    <div class="text-fmt">
                        <?= HTMLPurifier::process($bestAnswer->content); ?>
                    </div>
                    <div class="options clearfix mt-10">
                        <ul class="list-inline pull-right">
                            <li class="pull-right">
                                <a class="comments mr-10" data-toggle="collapse"
                                   href="#comments-answer-<?= $bestAnswer->id; ?>" aria-expanded="false"
                                   aria-controls="comment-<?= $bestAnswer->id; ?>"><i
                                        class="fa fa-comment-o"></i> <?= Yii::t('question', '{n, plural, =0{No comment} =1{One comment} other{# reviews}}', ['n' => $bestAnswer->comments]); ?>
                                </a>
                                <button class="btn btn-default btn-sm"
                                        data-source_id="<?= $bestAnswer->id; ?>" data-target="support-button"
                                        data-source_type="answer"
                                        data-support_num="<?= $bestAnswer->supports; ?>"><i
                                        class="fa fa-thumbs-o-up"></i>
                                    <?= $bestAnswer->supports; ?>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- 评论 -->
                    <?= \yuncms\comment\frontend\widgets\Comment::widget(['source_type' => 'answer', 'source_id' => $bestAnswer->id, 'hide_cancel' => false]) ?>
                    <!-- 评论结束 -->
                    <div class="media user-info border-top">
                        <div class="media-left">
                            <a href="<?= Url::to(['/user/space/view', 'id' => $bestAnswer->user_id]) ?>"
                               target="_blank">
                                <img class="avatar-40"
                                     src="<?= $bestAnswer->user->getAvatar('big') ?>"
                                     alt="<?= $bestAnswer->user->nickname ?>"></a>
                            </a>
                        </div>
                        <div class="media-body">

                            <div class="media-heading">
                                <strong><a href="<?= Url::to(['/user/space/view', 'id' => $bestAnswer->user_id]) ?>"
                                           class="mr5"><?= $bestAnswer->user->nickname ?></a>
                                    <span class="text-gold">
                                        <i
                                            class="fa fa-graduation-cap" aria-hidden="true" data-toggle="tooltip"
                                            data-placement="right" title=""
                                            data-original-title="已通过行家认证"></i>
                                    </span>
                                </strong>
                                <?php if ($bestAnswer->user->profile->city): ?>
                                    <span
                                        class="text-muted"> - <?= $bestAnswer->user->profile->city ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="content">
                                <span class="answer-time text-muted hidden-xs">
                                    <!--@if($bestAnswer->user->authentication && $bestAnswer->user->authentication->status === 1)
                                    擅长：{{ $bestAnswer->user->authentication->skill }} | @endif-->
                                    采纳率 <?= round($bestAnswer->user->userData->adoptions / $bestAnswer->user->extend->answers, 2) * 100 ?>
                                    % |
                                    回答于 <?= Yii::$app->formatter->asRelativeTime($bestAnswer->created_at); ?>
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endif; ?>
            <!-- 最佳答案结束-->
        </div>
        <!-- 回答开始 -->
        <div class="widget-answers mt-15">
            <div class="btn-group pull-right" role="group">
                <a class="btn btn-default btn-xs <?= ($answerOrder == 'supports') ? 'active' : '' ?>"
                   href="<?= Url::to(['view', 'id' => $model->id, 'alias' => $model->alias]) ?>">
                    <?= Yii::t('question', 'Default Sort') ?>
                </a>
                <a class="btn btn-default btn-xs <?= ($answerOrder == 'time') ? 'active' : '' ?>"
                   href="<?= Url::to(['view', 'id' => $model->id, 'alias' => $model->alias, 'answers' => 'time']) ?>">
                    <?= Yii::t('question', 'Time Sort') ?>
                </a>
            </div>
            <h2 class="h4 post-title"><?= Yii::t('question', '{n, plural, =0{No answers yet} =1{One answer} other{# answers}}', ['n' => $dataProvider->totalCount]); ?></h2>
            <?= ListView::widget([
                'options' => ['class' => null],
                'itemOptions' => ['class' => 'media'],
                'dataProvider' => $dataProvider,
                'itemView' => '_answer_item',//子视图
                'layout' => "{items}\n{pager}",
                'viewParams' => ['question' => $model],
            ]); ?>
            <div class="text-center">

            </div>
        </div>
        <?php if ($model->status != Question::STATUS_END): ?>
            <div class="widget-answer-form mt-15">
                <?php if (!Yii::$app->user->isGuest && ($model->user_id != Yii::$app->user->id && Answer::isAnswered(Yii::$app->user->id, $model->id))): ?>
                    <h4>我来回答</h4>
                    <?= \yuncms\question\widgets\Answer::widget(['questionId' => $model->id]); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-xs-12 col-md-3 side">
        <div class="widget-box">
            <ul class="widget-action list-unstyled">
                <!-- 关注部分 -->
                <li>
                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isFollowed($model, $model->id)): ?>
                        <button type="button" data-target="follow-button" class="btn btn-success btn-sm active"
                                data-source_type="question" data-source_id="<?= $model->id ?>" data-show_num="true"
                                data-toggle="tooltip" data-placement="right" title="" data-original-title="关注后将获得更新提醒">
                            已关注
                        </button>
                    <?php else: ?>
                        <button type="button" data-target="follow-button" class="btn btn-success btn-sm"
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
                        <button type="button" data-target="collect-button" class="btn btn-default btn-sm active"
                                data-source_type="question" data-source_id="<?= $model->id ?>" data-show_num="true"
                                data-toggle="tooltip" data-placement="right" title="" data-original-title="关注后将获得更新提醒">
                            已收藏
                        </button>
                    <?php else: ?>
                        <button type="button" data-target="collect-button" class="btn btn-default btn-sm"
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
                    <?php if (!$model->isHide()): ?>
                        <a href="<?= Url::to(['/user/space/view', 'id' => $model->user_id]) ?>"
                           target="_blank"><?= Html::encode($model->user->nickname) ?></a>
                    <?php endif; ?>
                    提出于 <?= Yii::$app->formatter->asRelativeTime($model->updated_at); ?></li>
            </ul>
        </div>
        <div class="widget-box">
            <h2 class="h4 widget-box__title">相似问题</h2>
            <ul class="widget-links list-unstyled list-text">
                <?= \yuncms\question\widgets\Popular::widget(['limit' => 10]); ?>
            </ul>
        </div>
    </div>
</div>
<?php
if (!Yii::$app->user->isGuest && $model->isAuthor()) {
    Modal::begin([
        'header' => '<h4 class="modal-title" id="adoptModalLabel">采纳回答</h4>',
        'options' => ['id' => 'adoptAnswer'],
        'footer' => '<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="adoptAnswerSubmit">采纳该回答</button>',
    ]);
    ?>
    <div class="alert alert-warning" role="alert" id="adoptAnswerAlert">
        <i class="fa fa-exclamation-circle"></i> 确认采纳该回答为最佳答案？
    </div>
    <blockquote id="answer_quote"></blockquote>
    <?php
    Modal::end();
    Modal::begin([
        'header' => '<h4 class="modal-title" id="adoptModalLabel">追加悬赏</h4>',
        'options' => ['id' => 'appendReward'],
        'footer' => '<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="appendRewardSubmit">确认追加</button>',
    ]);
    $this->registerJs('
    jQuery("#appendRewardSubmit").click(function(){
        var user_total_conis = \'14\';
        var reward = jQuery("#question_coins").val();

        if(reward>parseInt(user_total_conis)){
            jQuery("#rewardAlert").attr(\'class\',\'alert alert-warning\');
            jQuery("#rewardAlert").html(\'积分数不能大于\'+user_total_conis);
        }else{
            jQuery("#rewardAlert").empty();
            jQuery("#rewardAlert").attr(\'class\',\'\');
            jQuery("#rewardForm").submit();
        }
    });
');
    ?>
    <div class="alert alert-success" role="alert" id="rewardAlert">
        <i class="fa fa-exclamation-circle"></i> 提高悬赏，以提高问题的关注度！
    </div>
    <?php ActiveForm::begin([
        'layout' => 'inline',
        'action' => Url::to(['/question/question/append-reward', 'id' => $model->id]),
        'method' => 'post',
        'options' => ['id' => 'rewardForm']
    ]) ?>
    <div class="form-group">
        <label for="reward">追加</label>
        <select class="form-control" name="coins" id="question_coins">
            <option value="1" selected="">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="30">30</option>
            <option value="50">50</option>
            <option value="80">80</option>
            <option value="100">100</option>
        </select> 个积分
    </div>
    <div class="form-group">
        （您目前共有 <span class="text-gold"><?= Yii::$app->user->identity->extend->coins ?></span> 个积分）
    </div>
    <?php ActiveForm::end() ?>
    <?php
    Modal::end();
}
?>

