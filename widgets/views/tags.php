<?php
/**
 * @var yuncms\tag\models\Tag[] $models
 */

use yii\helpers\Url;
use yii\helpers\Html;
?>

<div class="panel panel-default">
    <div class="panel-heading"><?= Yii::t('question', 'Popular tags') ?></div>
    <div class="panel-body">
        <span class="question-tags">
            <?php foreach ($models as $tag): ?>
                <a href="<?= Url::to(['/question/question/tag', 'tag' => $tag['name']]) ?>"
                   class="label label-primary"
                   title="<?= Html::encode($tag['name']) ?>"
                   rel="tag"><?= Html::encode($tag['name']) ?></a>
            <?php endforeach; ?>
        </span>
    </div>
</div>

