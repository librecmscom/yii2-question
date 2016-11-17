<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use xutl\select2\Select2;
use xutl\summernote\SummerNote;

?>
<?php $form = ActiveForm::begin([]); ?>
<?= $form->field($model, 'title')->textInput(['class' => 'form-control input-lg', 'placeholder' => '请在这里概述您的问题',])->label(false); ?>
<?= $form->field($model, 'content')->widget(SummerNote::className(),[
    'uploadUrl'=>Url::to(['/question/question/sn-upload'])
]); ?>
<?= $form->field($model, 'tagValues')->widget(Select2::className(), [
    'options' => ['multiple' => true],
    'items' => ArrayHelper::map($model->tags, 'id', 'name'),
    'clientOptions' => [
        'placeholder' => Yii::t('app', 'Add the tag you are looking for'),
        'tags' => true,
        'minimumInputLength' => 2,
        'ajax' => [
            'url' => Url::to(['/question/question/auto-complete']),
            'dataType' => 'json',
            //延迟250ms发送请求
            'delay' => 250,
            'cache' => true,
            'data' => new \yii\web\JsExpression('function (params) {return {query: params.term};}'),
            'processResults' => new \yii\web\JsExpression('function (data) {return {results: data};}'),
        ],
    ],
]) ?>

<div class="row mt-20">
    <div class="col-md-8">
        <div class="form-inline" role="form">
            <?php if ($model->isNewRecord): ?>
                <?= $form->field($model, 'price', [
                    'template' => "{label}\n<div class=\"input-group\">{input}<div class=\"input-group-addon\">" . Yii::t('question', 'Point') . "</div></div>" . "\n{hint}\n{error}"
                ])->dropDownList([0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10]); ?>
                <span class="span-line">|</span>
            <?php endif; ?>
            <?= $form->field($model, 'hide')->checkbox() ?>
        </div>
    </div>
    <div class="col-md-4">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('question', 'Submit Question') : Yii::t('question', 'Update Question'), ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

