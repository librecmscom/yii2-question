<?php
/**
 * Leaps Framework [ WE CAN DO IT JUST THINK IT ]
 *
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2015 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

use Leaps\Helper\Url;
use Leaps\Helper\Html;
use Leaps\Bootstrap\Nav;
use Leaps\Widget\ListView;
use Leaps\Question\Asset;

/** @var \Leaps\Data\ActiveDataProvider $dataProvider */
Asset::register($this);
$this->title = Leaps::t('question', 'Tag') . ' ' . $tag;
$this->params['breadcrumbs'][] = Leaps::t('question', 'Questions');
$this->params['breadcrumbs'][] = Leaps::t('question', 'Tag');
$this->params['breadcrumbs'][] = $tag;
?>
<div class="row">
    <div class="col-md-9">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_item',//子视图
            'layout' => "{items}\n{pager}",
            'options' => ['class' => 'question-list list-group'],
            'itemOptions' => ['class' => 'list-group-item clearfix question-item']
        ]); ?>
    </div>
    <div class="col-md-3">
        <?= Html::a(Leaps::t('question', 'Ask a Question'), ['create'], ['class' => 'question-index-add-button btn btn-primary']); ?>

        <?= \Leaps\Question\Widget\Popular::widget(); ?>

        <?= \Leaps\Question\Widget\Tags::widget(); ?>

    </div>

</div>


