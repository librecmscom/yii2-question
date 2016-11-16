<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\bootstrap\ActiveForm;
use xutl\typeahead\Bloodhound;
use xutl\typeahead\TypeAheadAsset;
use yuncms\tag\widgets\TagsinputWidget;

/**
 * @var yii\web\View $this
 */
$engine = new Bloodhound([
    'name' => 'countriesEngine',
    'clientOptions' => [
        'datumTokenizer' => new JsExpression("Bloodhound.tokenizers.obj.whitespace('name')"),
        'queryTokenizer' => new JsExpression("Bloodhound.tokenizers.whitespace"),
        'remote' => [
            'url' => Url::to(['/question/question/auto-complete', 'query' => 'QRY']),
            'wildcard' => 'QRY'
        ],
    ]
]);
TypeAheadAsset::register($this);
$this->registerJs($engine->getClientScript());
$this->registerCss(".bootstrap-tagsinput {width: 100%;}");
?>
<?php $form = ActiveForm::begin([

]); ?>
<?= $form->field($model, 'title')->textInput(['class' => 'form-control input-lg', 'placeholder' => '请在这里概述您的问题',])->label(false); ?>
<?= $form->field($model, 'content')->textarea(['rows' => 5])->hint(Yii::t('question', 'Markdown powered content')); ?>
<?= $form->field($model, 'tagValues')->widget(TagsinputWidget::className(), [
    'options' => [
        'class' => 'form-control',
        'placeholder' => '添加标签'
    ],
    'clientOptions' => [
        'trimValue' => true,
        'typeaheadjs' => [
            'displayKey' => 'name',
            'valueKey' => 'name',
            'source' => $engine->getAdapterScript(),
        ]
    ]
]); ?>

<div class="row mt-20">
    <div class="col-md-8">
        <div class="form-inline">
            <?= $form->field($model, 'price', [
                'template' => "{label}\n{input} " . Yii::t('question', 'Point') . "\n{hint}\n{error}"
            ])->dropDownList([0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10]); ?>
            <span class="span-line">|</span>
            <?= $form->field($model, 'hide')->checkbox() ?>
        </div>
    </div>
    <div class="col-md-4">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('question', 'Submit Question') : Yii::t('question', 'Update Question'), ['class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

