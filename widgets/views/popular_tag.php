<?php
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var \yuncms\tag\models\Tag $tags
 * @var string $css
 */
?>
<ul <?= !empty($class) ? 'class="' . $class . '"' : ''; ?>>
    <?php
    foreach ($tags as $tag) {
        echo Html::tag('li', Html::a($tag->name, ['/question/question/tag', 'tag' => $tag->name]));
    }
    ?>
</ul>
