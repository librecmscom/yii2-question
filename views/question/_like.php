<?php
/**
 * Leaps Framework [ WE CAN DO IT JUST THINK IT ]
 *
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2015 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
use Leaps\Question\Model\Vote;
use Leaps\Helper\Url;
?>
<span class="question-like js-vote">
    <?php if (Vote::isUserCan($model, Leaps::$app->user->id)): ?>
        <a class="btn btn-success btn-sm js-vote-up"
           href="<?= Url::to([$route, 'id' => $model->id, 'vote' => 'up']) ?>"
           title="<?= Leaps::t('question', 'Like') ?>">
            <span class="glyphicon glyphicon-heart"></span> <?= $model->votes ?>
        </a>
    <?php else: ?>
        <span class="btn btn-success btn-sm disabled js-vote-up" title="<?= Leaps::t('question', 'Like') ?>">
            <span class="glyphicon glyphicon-heart"></span> <?= $model->votes ?>
        </span>
    <?php endif; ?>
</span>
