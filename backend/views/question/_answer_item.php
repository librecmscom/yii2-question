<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use yuncms\question\models\Question;
use yuncms\comment\frontend\widgets\Comment;
/**
 * @var yuncms\question\models\Answer $model
 * @var yuncms\question\models\Question $question
 */
?>

<div class="media-left">
    <a href="<?= Url::to(['/user/space/view', 'id' => $model->user_id]) ?>" class="avatar-link user-card"
       target="_blank">
        <img class="avatar-40" src="<?= $model->user->getAvatar('big') ?>" alt="<?= $model->user->nickname ?>"></a>
    </a>
</div>
<div class="media-body">
    <div class="media-heading">
        <strong>
            <a href="<?= Url::to(['/user/space/view', 'id' => $model->user_id]) ?>"
               class="mr-5 user-card"><?= $model->user->nickname ?></a>
                <span class="text-gold">
                    <i
                        class="fa fa-graduation-cap" aria-hidden="true" data-toggle="tooltip" data-placement="right"
                        title="" data-original-title="已通过行家认证"></i>
                                </span>
        </strong>
        <?php if ($model->user->profile->city): ?>
            <span class="text-muted"> - <?= $model->user->profile->city ?></span>
        <?php endif; ?>
        <span
            class="answer-time text-muted hidden-xs"><?= Yii::$app->formatter->asRelativeTime($model->updated_at) ?></span>
    </div>
    <div class="content">

        <!--<span class="text-muted">擅长：擅长</span>-->
        <?= HTMLPurifier::process($model->content); ?>
       
    </div>
    <div class="media-footer">
        <ul class="list-inline mb-20">
            <li><a class="comments" data-toggle="collapse" href="#comments-answer-<?= $model->id ?>"
                   aria-expanded="false" aria-controls="comment-<?= $model->id ?>"><i class="fa fa-comment-o"></i>
                    <?= Yii::t('question', '{n, plural, =0{No comment} =1{One comment} other{# reviews}}', ['n' => $model->comments]); ?>
                    </a></li>
            <?php if (!Yii::$app->user->isGuest): ?>
                <?php if ($question->status == Question::STATUS_ACTIVE && $model->isAuthor()): ?>
                    <li><a href="<?= Url::to(['/question/answer/update', 'id' => $model->id]) ?>"
                           data-toggle="tooltip"
                           data-placement="right" title="" data-original-title="继续完善回答内容"><i class="fa fa-edit"></i>
                            编辑</a>
                    </li>
                <?php endif; ?>
                <?php if ($question->status == Question::STATUS_ACTIVE && $question->isAuthor()): ?>
                    <li><a href="#" class="adopt-answer" data-toggle="modal" data-target="#adoptAnswer"
                           data-answer_id="<?= $model->id ?>"
                           data-answer_content="<?= mb_substr(strip_tags($model->content), 0, 200); ?>"><i
                                class="fa fa-check-square-o"></i> 采纳为最佳答案</a></li>
                <?php endif; ?>
            <?php endif; ?>
            <li class="pull-right">
                <button class="btn btn-default btn-sm" data-target="support-button" data-source_id="<?= $model->id ?>"
                        data-source_type="answer" data-support_num="<?= $model->supports ?>"><i
                        class="fa fa-thumbs-o-up"></i> <?= $model->supports ?>
                </button>
            </li>
        </ul>
    </div>
    <?=Comment::widget(['source_type'=>'answer','source_id'=>$model->id,'hide_cancel'=>false])?>
</div>

