<?php
/**
 * Leaps Framework [ WE CAN DO IT JUST THINK IT ]
 *
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2015 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
use Leaps\Helper\Html;
use Leaps\Helper\Url;
use Leaps\Question\Model\Vote;
use Leaps\Question\Model\Question;

/**
 * @var Leaps\Question\Model\Answer $model
 * @var Leaps\Question\Model\Question $question
 */
?>
<article class="question-view-answer row">
    <section class="question-view-answer-aside col-md-2">
        <div class="question-created">
            <?= Html::tag('div', Html::encode($model->user->username), ['class' => 'question-user']); ?>
            <?= Html::tag('div', Leaps::$app->formatter->asRelativeTime($model->updated_at), ['class' => 'question-time']); ?>
        </div>
        <div class="question-answer-like">
            <span class="question-answer-correct js-answer-correct">
                <?php if ($question->isAuthor()): ?>
                    <a title="<?= Leaps::t('question', 'Mark answer as correct') ?>"
                       class="btn btn-sm question-answer-correct-link js-answer-correct-link <?= ($model->isCorrect()) ? 'btn-warning' : 'btn-default' ?>"
                       href="<?= Url::to(['/question/question/answer-correct', 'id' => $model->id, 'question_id' => $question->id]) ?>">
                        <span class="glyphicon glyphicon-ok"></span>
                    </a>
                <?php else: ?>
                    <?= $model->isCorrect() ? Html::tag('span', Html::tag('span', '', ['class' => 'glyphicon glyphicon-ok']), ['class' => 'btn btn-warning btn-sm question-answer-correct-button']) : ''; ?>
                <?php endif; ?>
            </span>


            <span class="question-like js-answer-vote">
                <?php if (Vote::isUserCan($model, Leaps::$app->user->id)): ?>
                    <a class="btn btn-success btn-sm js-answer-vote-up"
                       href="<?= Url::to(['answer-vote', 'id' => $model->id, 'vote' => 'up']) ?>"
                       title="<?= Leaps::t('question', 'Like') ?>">
                        <span class="glyphicon glyphicon-heart"></span> <?= $model->votes ?>
                    </a>
                <?php else: ?>
                    <span class="btn btn-success btn-sm disabled js-vote-up" title="<?= Leaps::t('question', 'Like') ?>">
                        <span class="glyphicon glyphicon-heart"></span> <?= $model->votes ?>
                    </span>
                <?php endif; ?>
            </span>

        </div>

        <?php if ($model->isAuthor()) : ?>
            <span class="question-edit-links">
                <?=Html::a(Html::tag('span','',['class'=>'glyphicon glyphicon-pencil']),['/question/question/answer-update', 'id' => $model->id],['class'=>'label label-success']);?>
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
