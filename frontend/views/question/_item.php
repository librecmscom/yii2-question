<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yuncms\question\models\Question;

/**
 * @var yii\web\View $this
 * @var yuncms\question\models\Question $model
 */
?>

    <div class="qa-rank">
        <div class="answers <?= $model->status == Question::STATUS_END ? 'solved' : 'answered' ?>">
            <?= $model->answers ?>
            <small><?= $model->status == Question::STATUS_END ? '解决' : '回答' ?></small>
        </div>
        <div class="views hidden-xs">
            <?= $model->views ?>
            <small>浏览</small>
        </div>
    </div>
    <div class="summary">
        <ul class="author list-inline">
            <li>
                <a href="<?= Url::to(['/user/space/view', 'id' => $model->user_id]) ?>"><?= Html::encode($model->user->nickname) ?></a>
                <span class="split"></span>
                <span class="askDate"><?= Yii::$app->formatter->asRelativeTime($model->updated_at) ?></span>
            </li>
        </ul>
        <h2 class="title">
            <?php if ($model->price > 0): ?>
                <span class="text-gold"><i class="fa fa-database"></i> <?= intval($model->price) ?></span>
            <?php endif; ?>
            <a href="<?= Url::to(['view', 'id' => $model->id, 'alias' => $model->alias]) ?>"><?= Html::encode($model->title) ?></a>
        </h2>
        <?php if ($model->tags): ?>
            <ul class="taglist-inline ib">
                <?php
                foreach ($model->tags as $tag):?>
                    <li class="tagPopup">
                        <a class="tag" href="<?= Url::to(['/question/question/tag', 'tag' => $tag->name]) ?>"><?= Html::encode($tag->name) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

