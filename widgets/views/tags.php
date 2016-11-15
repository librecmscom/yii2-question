<?php
/**
 * @var yuncms\tag\models\Tag[] $models
 */

use yii\helpers\Url;
use yii\helpers\Html;

?>
<div class="widget-box">
    <h2 class="h4 widget-box-title">热议话题 <a href="<?= Url::to(['/tag/index']); ?>" title="更多">»</a></h2>
    <ul class="taglist-inline multi">
        <?php foreach ($models as $tag): ?>
            <li class="tagPopup">
                <a class="tag" rel="tag"
                   href="<?= Url::to(['/question/question/tag', 'tag' => Html::encode($tag->name)]); ?>"><?= Html::encode($tag->name) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

