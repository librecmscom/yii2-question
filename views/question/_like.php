<?php
use yii\helpers\Url;
use yuncms\question\models\vote;

?>
<span class="question-like js-vote">
    <?php if (Vote::isUserCan($model, Yii::$app->user->id)): ?>
        <a class="btn btn-success btn-sm js-vote-up"
           href="<?= Url::to([$route, 'id' => $model->id, 'vote' => 'up']) ?>"
           title="<?= Yii::t('question', 'Like') ?>">
            <span class="glyphicon glyphicon-heart"></span> <?= $model->votes ?>
        </a>
    <?php else: ?>
        <span class="btn btn-success btn-sm disabled js-vote-up" title="<?= Yii::t('question', 'Like') ?>">
            <span class="glyphicon glyphicon-heart"></span> <?= $model->votes ?>
        </span>
    <?php endif; ?>
</span>
