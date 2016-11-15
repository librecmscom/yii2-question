<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\widgets\ListView;
use yuncms\question\Asset;
use yuncms\question\widgets\Popular;
use yuncms\question\widgets\Tags;
use yuncms\question\models\Question;

/** @var \yii\data\ActiveDataProvider $dataProvider */
Asset::register($this);
$this->title = Yii::t('question', 'Questions');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row mt-10">
    <div class="col-xs-12 col-md-9 main">
        <?= Nav::widget([
            'options' => ['class' => 'nav nav-tabs nav-tabs-zen mb10'],
            'items' => [
                ['label' => Yii::t('question', 'New question'), 'url' => ['/question/question/index'],
                    'active' => !isset($_GET['order']) ? true : ($_GET['order'] == 'new' ? true : false)],
                ['label' => Yii::t('question', 'Hot question'), 'url' => ['/question/question/index', 'order' => 'hottest']],
                ['label' => Yii::t('question', 'Reward question'), 'url' => ['/question/question/index', 'order' => 'reward']],
                ['label' => Yii::t('question', 'Unanswered question'), 'url' => ['/question/question/index', 'order' => 'unanswered']],
            ]
        ]); ?>

        <div class="stream-list question-stream">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_item',//子视图
                'layout' => "{items}\n{pager}",
                'options' => ['class' => 'stream-list question-stream'],
                'itemOptions' => ['class' => 'stream-list-item']
            ]); ?>
        </div><!-- /.stream-list -->

        <div class="text-center">
            {!! str_replace('/?', '?', $questions->render()) !!}
        </div>
    </div><!-- /.main -->
    <div class="col-xs-12 col-md-3 side">
        <div class="side-alert alert alert-warning">
            <p>今天，你的网站遇到什么问题呢？</p>
            <?= Html::a(Yii::t('question', 'Ask a Question'), ['create'], ['class' => 'btn btn-primary btn-block mt-10']); ?>
        </div>

        <div class="widget-box">
            <h2 class="h4 widget-box-title">热议话题 <a href="{{ route('website.topic') }}" title="更多">»</a></h2>
            <ul class="taglist-inline multi">
                @foreach($hotTags as $hotTag)
                <li class="tagPopup"><a class="tag" data-toggle="popover"
                                        href="{{ route('ask.tag.index',['name'=>$hotTag->name]) }}">{{ $hotTag->name
                        }}</a></li>
                @endforeach
            </ul>
        </div>

        <div class="widget-box mt30">
            <h2 class="widget-box-title">
                回答榜
                <a href="{{ route('auth.top.coins') }}" title="更多">»</a>
            </h2>
            <ol class="widget-top10">
                @foreach($topAnswerUsers as $index => $topAnswerUser)
                <li class="text-muted">
                    <img class="avatar-32"
                         src="{{ route('website.image.avatar',['avatar_name'=>$topAnswerUser['id'].'_middle'])}}">
                    <a href="{{ route('auth.space.index',['user_id'=>$topAnswerUser['id']]) }}" class="ellipsis">{{
                        $topAnswerUser['name'] }}</a>
                    <span class="text-muted pull-right">{{ $topAnswerUser['answers'] }} 回答</span>
                </li>
                @endforeach

            </ol>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-9 main">
         <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_item',//子视图
            'layout' => "{items}\n{pager}",
            'options' => ['class' => 'stream-list question-stream'],
            'itemOptions' => ['class' => 'stream-list-item']
        ]); ?>
    </div>
    <div class="col-xs-12 col-md-3 side">
        <?= Html::a(Yii::t('question', 'Ask a Question'), ['create'], ['class' => 'question-index-add-button btn btn-primary']); ?>

        <?= Popular::widget(); ?>

        <?= Tags::widget(); ?>

    </div>

</div>


