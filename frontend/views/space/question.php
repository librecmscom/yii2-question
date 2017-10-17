<?php

use yii\bootstrap\Nav;
use yii\widgets\ListView;

/* @var yii\web\View $this */
/* @var yuncms\user\models\User $model */

$this->context->layout = '@yuncms/space/frontend/views/layouts/space';
$this->params['user'] = $user;
if (!Yii::$app->user->isGuest && Yii::$app->user->id == $user->id) {//Me
    $this->title = Yii::t('question', 'My Questions');
} else {
    $this->title = Yii::t('question', 'His Questions');
}
?>

<div class="stream-following">
    <h4 class="profile-mine__heading">
        <span><?=Yii::t('question', 'My Questions')?></span>

    </h4>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'tag' => 'ul',
            'class' => 'profile-mine__content mb-20',
        ],
        'layout' => "{items}\n{pager}",
        'itemOptions' => ['tag' => 'li'],
        'itemView' => '_item',//子视图
    ]); ?>
</div>