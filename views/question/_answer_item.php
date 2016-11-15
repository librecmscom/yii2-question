<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yuncms\question\models\Question;

/**
 * @var yuncms\question\models\Answer $model
 * @var yuncms\question\models\Question $question
 */
?>
<!--
<article class="question-view-answer row">
    <section class="question-view-answer-aside col-md-2">
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
        </div>
    </section>
    <section class="panel <?= ($model->isCorrect()) ? 'panel-warning' : 'panel-default' ?> col-md-10">

    </section>
</article>-->

<div class="media">
    <div class="media-left">
        <a href="<?= Url::to(['/user/profile/show', 'id' => $model->user_id]) ?>" class="avatar-link user-card"
           target="_blank">
            <img class="avatar-40" src="<?= $model->user->getAvatar('big')?>" alt="<?= $model->user->username ?>"></a>
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
                        <li><a href="<?= Url::to(['/question/answer/update', 'id' => $model->id]) ?>"
                               data-toggle="tooltip"
                               data-placement="right" title="" data-original-title="继续完善回答内容"><i class="fa fa-edit"></i>
                                编辑</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($question->status == Question::STATUS_ACTIVE && !$question->isAuthor()): ?>
                        <li><a href="#" class="adopt-answer" data-toggle="modal" data-target="#adoptAnswer"
                               data-answer_id="<?= $model->id ?>"
                               data-answer_content="<?=mb_substr($answer->content,0,200);?>"><i
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
        <div class="modal" id="adoptAnswer" tabindex="-1" role="dialog" aria-labelledby="adoptAnswerLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="adoptModalLabel">采纳回答</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert" id="adoptAnswerAlert">
                            <i class="fa fa-exclamation-circle"></i> 确认采纳该回答为最佳答案？
                        </div>
                        <blockquote id="answer_quote"></blockquote>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-primary" id="adoptAnswerSubmit">采纳该回答</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

