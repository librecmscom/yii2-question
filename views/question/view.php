<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yuncms\question\Asset;
use yuncms\question\models\Question;
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
            <h4 class="title">
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
                    <?= $model->body ?>
                </div>
                <div class="post-opt mt-10">
                    <ul class="list-inline">
                        <li><a class="comments"  data-toggle="collapse"  href="#comments-question-<?=$model->id?>" aria-expanded="false" aria-controls="comment-<?=$model->id?>"><i class="fa fa-comment-o"></i> <?=$model->comments?> 条评论</a></li>

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
                <!-- 分享 -->
                <div class="mb-10">
                    {!! Setting()->get('website_share_code')  !!}
                </div>
            </div>

            <!-- 最佳答案 -->
            <?php if($model->status=== Question::STATUS_END && $bestAnswer):?>
            <div class="best-answer mt-10">
                <div class="trophy-title">
                    <h3>
                        <i class="fa fa-trophy"></i> 最佳答案
                        <span class="pull-right text-muted adopt_time">{{ timestamp_format($bestAnswer->created_at) }}</span>
                    </h3>
                </div>
                <div class="text-fmt">
                    {!! $bestAnswer->content !!}
                </div>
                <div class="options clearfix mt-10">
                    <ul class="list-inline pull-right">
                        <li class="pull-right">
                            <a class="comments mr-10" data-toggle="collapse" href="#comments-answer-{{ $bestAnswer->id }}" aria-expanded="false" aria-controls="comment-{{ $bestAnswer->id }}"><i class="fa fa-comment-o"></i> {{ $bestAnswer->comments }} 条评论</a>
                            <button class="btn btn-default btn-sm btn-support" data-source_id="{{ $bestAnswer->id }}" data-source_type="answer" data-support_num="{{ $bestAnswer->supports }}"><i class="fa fa-thumbs-o-up"></i> {{ $bestAnswer->supports }}</button>
                        </li>
                    </ul>
                </div>
                @include('theme::comment.collapse',['comment_source_type'=>'answer','comment_source_id'=>$bestAnswer->id,'hide_cancel'=>false])

                <div class="media user-info border-top">
                    <div class="media-left">
                        <a href="{{ route('auth.space.index',['user_id'=>$bestAnswer->user_id]) }}" target="_blank">
                            <img class="avatar-40"  src="{{ route('website.image.avatar',['avatar_name'=>$bestAnswer->user_id.'_middle']) }}" alt="{{ $bestAnswer->user->name }}"></a>
                        </a>
                    </div>
                    <div class="media-body">

                        <div class="media-heading">
                            <strong><a href="{{ route('auth.space.index',['user_id'=>$bestAnswer->user_id]) }}" class="mr5">{{ $bestAnswer->user->name }}</a> <span class="text-gold">@if($bestAnswer->user->authentication && $bestAnswer->user->authentication->status === 1)<i class="fa fa-graduation-cap" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="" data-original-title="已通过行家认证"></i>@endif</span></strong>
                            @if($bestAnswer->user->title)
                            <span class="text-muted"> - {{ $bestAnswer->user->title }}</span>
                            @endif
                        </div>

                        <div class="content">
                            <span class="answer-time text-muted hidden-xs">@if($bestAnswer->user->authentication && $bestAnswer->user->authentication->status === 1)擅长：{{ $bestAnswer->user->authentication->skill }} | @endif采纳率 {{ $bestAnswer->user->userData->adoptPercent() }}% | 回答于 {{ timestamp_format($bestAnswer->created_at) }}</span>
                        </div>
                    </div>

                </div>
            </div>
            <?php endif;?>
            <!-- 最佳答案结束-->
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
