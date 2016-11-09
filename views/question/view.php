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
use Leaps\Question\Asset;
use Leaps\Widget\ListView;
use Leaps\Widget\LinkPager;
use Leaps\Question\Model\Vote;

/**
 * @var Leaps\Web\View $this
 * @var Leaps\Question\Model\Question $model
 */

Asset::register($this);
$this->title = Html::encode($model->title);
$this->params['breadcrumbs'][] = ['label' => Leaps::t('question', 'Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$answerOrders = [
    'Active' => 'active',
    'Oldest' => 'oldest',
    'Votes' => 'votes'
];
?>

<section class="question-view">
    <!-- 主题开始 -->
    <article class="question-view-question row">
        <header class="page-header col-md-12">
            <h1 class="question-view-title">
                <?= Html::encode($this->title) ?>
                <?php if ($model->isDraft()): ?>
                    <?= Html::tag('small', Html::tag('span', Leaps::t('question', 'Draft'), ['class' => 'label label-default'])); ?>
                <?php endif; ?>
            </h1>
        </header>
        <section class="question-view-aside col-md-2" role="aside">
            <div class="question-created">
                <?= Html::tag('div', Html::encode($model->user->username), ['class' => 'question-user']); ?>
                <?= Html::tag('div', Leaps::$app->formatter->asRelativeTime($model->updated_at), ['class' => 'question-time']); ?>
            </div>

            <div class="question-view-actions">
                <!--顶踩部分-->
                <div class="question-vote js-vote">
                    <?php if (Vote::isUserCan($model, Leaps::$app->user->id)): ?>
                        <a class="question-vote-up js-vote-up"
                           href="<?= Url::to(['vote', 'id' => $model->id, 'vote' => 'up']) ?>"
                           title="<?= Leaps::t('question', 'Vote up') ?>">
                            <span class="glyphicon glyphicon-chevron-up"></span>
                        </a>
                    <?php else: ?>
                        <span class="question-vote-up question-vote-up-disabled js-vote-up" title="<?= Leaps::t('question', 'Vote up') ?>">
            <span class="glyphicon glyphicon-chevron-up"></span>
        </span>
                    <?php endif; ?>
                    <span class="question-vote-count"><?= $model->votes ?></span>
                    <?php if (Vote::isUserCan($model, Leaps::$app->user->id)): ?>
                        <a class="question-vote-down js-vote-down"
                           href="<?= Url::to(['vote', 'id' => $model->id, 'vote' => 'down']) ?>"
                           title="<?= Leaps::t('question', 'Vote down') ?>">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </a>
                    <?php else: ?>
                        <span class="question-vote-down question-vote-down-disabled js-vote-down"
                              title="<?= Leaps::t('question', 'Vote down') ?>">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- 收藏部分 -->
                <div class="question-favorite js-favorite <?= $model->isFavorite() ? 'question-favorite-active' : '' ?>">
                    <a class="question-favorite-link js-favorite-link"
                       href="<?= Url::to(['favorite', 'id' => $model->id]) ?>">
                        <span class="glyphicon <?= ($model->isFavorite()) ? 'glyphicon-star' : 'glyphicon-star-empty' ?>"></span>
                    </a>
                    <div class="question-favorite-count"><?= $model->getFavoriteCount() ?></div>
                </div>
            </div>
        </section>
        <section class="question-view-body col-md-10" role="main">
            <div class="panel panel-default">
                <section class="panel-body question-view-text">
                    <?= $model->body ?>
                </section>
                <footer class="panel-footer">
                    <div class="question-view-meta">

                        <?php if ($model->isAuthor()) : ?>
                            <span class="question-edit-links">
                                <a href="<?= Url::to(['update', 'id' => $model->id]) ?>" title="<?= Leaps::t('question', 'Edit'); ?>"
       class="label label-success"><span class="glyphicon glyphicon-pencil"></span></a>
                                <?= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-remove']), ['delete', 'id' => $model->id],[
                                    'class'=>'label label-danger',
                                    'data'=>[
                                        'confirm'=>Leaps::t('question', 'Sure?'),
                                        'method'=>'post'
                                    ],
                                ]); ?>


    </span>
                        <?php endif; ?>

                        <!--问题标签  -->
                        <span class="question-tags">
                            <?php foreach ($model->tags as $tag): ?>
                                <a href="<?= Url::to(['/question/question/tag', 'tag' => $tag->name]) ?>" class="label label-primary"
                                   title="<?= Html::encode($tag->name) ?>"
                                   rel="tag"><?= Html::encode($tag->name) ?></a>
                            <?php endforeach; ?>
                        </span>
                    </div>
                </footer>
            </div>
        </section>
    </article>

    <!-- 回答开始 -->
    <div class="question-view-answers row">
        <div class="btn-group pull-right" role="group">
            <?php foreach ($answerOrders as $aId => $aOrder): ?>
                <a class="btn btn-default btn-xs <?= ($aOrder == $answerOrder) ? 'active' : '' ?>"
                    href="<?= Url::to(['view', 'id' => $model->id, 'alias' => $model->alias, 'answers' => $aOrder]) ?>">
                        <?= Leaps::t('question', $aId) ?>
                    </a>
            <?php endforeach; ?>
        </div>
        <h2 class="title h4 mt30 mb20 post-title" id="answers-title">
            <?= Leaps::t('question', '{n, plural, =0{No answers yet} =1{One answer} other{# answers}}', ['n' => $dataProvider->totalCount]); ?>
        </h2>
        <!-- 答案列表开始 -->

        <div class="question-view-answers-list col-md-12">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_answer_item',//子视图
                'layout' => "{items}\n{pager}",
                'viewParams' => ['question' => $model],
            ]); ?>
        </div>
        <div class="question-view-answer-form">
            <?=\Leaps\Question\Widget\Answer::widget(['questionId'=>$model->id]);?>
        </div>
    </div>
</section>
