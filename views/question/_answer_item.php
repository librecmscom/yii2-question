<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yuncms\question\models\Vote;
use yuncms\question\models\Question;

/**
 * @var yuncms\question\models\Answer $model
 * @var yuncms\question\models\Question $question
 */
?>
<article class="question-view-answer row">
    <section class="question-view-answer-aside col-md-2">
        <div class="question-created">
            <?= Html::tag('div', Html::encode($model->user->username), ['class' => 'question-user']); ?>
            <?= Html::tag('div', Yii::$app->formatter->asRelativeTime($model->updated_at), ['class' => 'question-time']); ?>
        </div>
        <div class="question-answer-like">
            <span class="question-answer-correct js-answer-correct">
                <?php if ($question->isAuthor()): ?>
                    <a title="<?= Yii::t('question', 'Mark answer as correct') ?>"
                       class="btn btn-sm question-answer-correct-link js-answer-correct-link <?= ($model->isCorrect()) ? 'btn-warning' : 'btn-default' ?>"
                       href="<?= Url::to(['/question/question/answer-correct', 'id' => $model->id, 'question_id' => $question->id]) ?>">
                        <span class="glyphicon glyphicon-ok"></span>
                    </a>
                <?php else: ?>
                    <?= $model->isCorrect() ? Html::tag('span', Html::tag('span', '', ['class' => 'glyphicon glyphicon-ok']), ['class' => 'btn btn-warning btn-sm question-answer-correct-button']) : ''; ?>
                <?php endif; ?>
            </span>


            <span class="question-like js-answer-vote">
                <?php if (Vote::isUserCan($model, Yii::$app->user->id)): ?>
                    <a class="btn btn-success btn-sm js-answer-vote-up"
                       href="<?= Url::to(['answer-vote', 'id' => $model->id, 'vote' => 'up']) ?>"
                       title="<?= Yii::t('question', 'Like') ?>">
                        <span class="glyphicon glyphicon-heart"></span> <?= $model->votes ?>
                    </a>
                <?php else: ?>
                    <span class="btn btn-success btn-sm disabled js-vote-up" title="<?= Yii::t('question', 'Like') ?>">
                        <span class="glyphicon glyphicon-heart"></span> <?= $model->votes ?>
                    </span>
                <?php endif; ?>
            </span>

        </div>

        <?php if ($model->isAuthor()) : ?>
            <span class="question-edit-links">
                <?= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-pencil']), ['/question/question/answer-update', 'id' => $model->id], ['class' => 'label label-success']); ?>
            </span>
        <?php endif; ?>


    </section>
    <section class="panel <?= ($model->isCorrect()) ? 'panel-warning' : 'panel-default' ?> col-md-10">
        <section class="panel-body">
            <div class="question-view-text">
                <?= $model->body ?>
            </div>
        </section>
    </section>
</article>

<div class="media">
    <div class="media-left">
        <a href="{{ route('auth.space.index',['user_id'=>$answer->user_id]) }}" class="avatar-link user-card"
           target="_blank">
            <img class="avatar-40" src="{{ route('website.image.avatar',['avatar_name'=>$answer->user_id.'_middle']) }}"
                 alt="{{ $answer->user['name'] }}"></a>
        </a>
    </div>
    <div class="media-body">
        <div class="media-heading">
            <strong>
                <a href="<?= Url::to(['/user/profile/show', 'id' => $model->user_id]) ?>"
                   class="mr-5 user-card"><?= $model->user->username ?></a>
                <span class="text-gold">
                    <i
                        class="fa fa-graduation-cap" aria-hidden="true" data-toggle="tooltip" data-placement="right"
                        title="" data-original-title="已通过行家认证"></i>
                                </span>
            </strong>
            <?php if ($model->user->username): ?>
                <span class="text-muted"> - <?= $model->user->username ?></span>
            <?php endif; ?>
            <span
                class="answer-time text-muted hidden-xs"><?= Yii::$app->formatter->asRelativeTime($model->updated_at) ?></span>
        </div>
        <div class="content">

            <span class="text-muted">擅长：擅长</span>

            <p><?= $model->body ?></p>
        </div>
        <div class="media-footer">
            <ul class="list-inline mb-20">
                <li><a class="comments" data-toggle="collapse" href="#comments-answer-<?= $model->id ?>"
                       aria-expanded="false" aria-controls="comment-<?= $model->id ?>"><i class="fa fa-comment-o"></i>
                        <?= $model->comments ?> 条评论</a></li>
                <?php if (!Yii::$app->user->isGuest): ?>
                    <?php if ($question->status == Question::STATUS_ACTIVE && $model->isAuthor()): ?>
                        <li><a href="<?= Url::to(['/question/question/answer-update', 'id' => $model->id]) ?>"
                               data-toggle="tooltip"
                               data-placement="right" title="" data-original-title="继续完善回答内容"><i class="fa fa-edit"></i>
                                编辑</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($question->status == Question::STATUS_ACTIVE && $question->isAuthor()): ?>
                        <li><a href="#" class="adopt-answer" data-toggle="modal" data-target="#adoptAnswer"
                               data-answer_id="<?= $model->id ?>"
                               data-answer_content="{{ str_limit($answer->content,200) }}"><i
                                    class="fa fa-check-square-o"></i> 采纳为最佳答案</a></li>
                    <?php endif; ?>
                <?php endif; ?>
                <li class="pull-right">
                    <button class="btn btn-default btn-sm btn-support" data-source_id="<?= $model->id ?>"
                            data-source_type="answer" data-support_num="<?= $model->supports ?>"><i
                            class="fa fa-thumbs-o-up"></i> <?= $model->supports ?>
                    </button>
                </li>
            </ul>
        </div>
        <?= \yuncms\comment\widgets\Comment::widget(['source_type' => 'question','source_id' => $model->id]); ?>
    </div>
</div>

