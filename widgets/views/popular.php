<?php
/**
 * @var yuncms\question\models\Question[] $models
 */
use yii\helpers\Url;
use yii\helpers\Html;

?>

<div class="panel panel-default">
    <div class="panel-heading"><?= Yii::t('question', 'Popular Questions') ?></div>
    <ul class="question-questions-list list-group">
        <?php if (!empty($models)): ?>
            <?php foreach ($models as $model): ?>
                <li class="list-group-item">
                    <a href="<?= Url::to(['/question/question/view', 'id' => $model->id, 'alias' => $model->alias]) ?>"
                       title="<?= Html::encode($model->title) ?>">
                        <?= Html::encode($model->title) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="list-group-item"><?= Yii::t('question', 'No popular questions') ?></li>
        <?php endif; ?>
    </ul>
</div>

