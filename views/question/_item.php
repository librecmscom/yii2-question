<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yuncms\question\models\Question;

/**
 * @var yii\web\View $this
 * @var yuncms\question\models\Question $model
 */
?>

<div class="question-panels">
    <div class="question-panel votes">
        <?= Html::tag('div', $model->votes, ['class' => 'question-panel-count']); ?>
        <?= Html::tag('div', Yii::t('question', 'Votes')); ?>
    </div>
    <div class="question-panel <?= ($model->answers > 0) ? 'status-answered' : 'status-unanswered' ?>">
        <?= Html::tag('div', $model->answers, ['class' => 'question-panel-count']); ?>
        <?= Html::tag('div', Yii::t('question', 'Answers')); ?>
    </div>
    <div class="question-panel views">
        <?= Html::tag('div', $model->views, ['class' => 'question-panel-count']); ?>
        <?= Html::tag('div', Yii::t('question', 'Views')); ?>
    </div>
</div>
<div class="question-summary">
    <div class="question-meta">
        <div class="question-created">
            <?= Html::tag('div', Html::encode($model->user->username), ['class' => 'question-user']); ?>
            <?= Html::tag('div', Yii::$app->formatter->asRelativeTime($model->updated_at), ['class' => 'question-time']); ?>
        </div>

        <?php if ($model->isAuthor()) : ?>
            <span class="question-edit-links">
                    <a href="<?= Url::to(['update', 'id' => $model->id]) ?>"
                       title="<?= Yii::t('question', 'Edit'); ?>"
                       class="label label-success"><span
                            class="glyphicon glyphicon-pencil"></span></a>
                <?php if ($model instanceof Question): ?>
                    <a href="<?= Url::to(['delete', 'id' => $model->id]) ?>"
                       title="<?= Yii::t('question', 'Delete'); ?>"
                       class="label label-danger"
                       data-confirm="<?= Yii::t('question', 'Sure?'); ?>" data-method="post" data-pjax="0"><span
                            class="glyphicon glyphicon-remove"></span></a>
                <?php endif; ?>
                </span>
        <?php endif; ?>
    </div>
    <h4 class="question-heading list-group-item-heading">
        <?= Html::a(Html::encode($model->title), ['view', 'id' => $model->id, 'alias' => $model->alias], ['class' => 'question-link', 'title' => Html::encode($model->title)]); ?>
        <?php if ($model->isDraft()): ?>
            <small><span class="label label-default"><?= Yii::t('question', 'Draft') ?></span></small>
        <?php endif; ?>
    </h4>
    <div class="question-tags">
            <span class="question-tags">
                <?php
                foreach ($model->tags as $tag) {
                    echo Html::a(Html::encode($tag->name), ['/question/question/tag', 'tag' => $tag->name], ['class' => 'label label-primary', 'title' => Html::encode($tag->name), 'rel' => 'tag']);

                }
                ?>
            </span>
    </div>
</div>


