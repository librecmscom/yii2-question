<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use xutl\typeahead\Bloodhound;
use xutl\typeahead\TypeAheadPluginAsset;
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
TypeAheadPluginAsset::register($this);
$this->registerJs($engine->getClientScript());
$this->registerCss(".bootstrap-tagsinput {width: 100%;}");
?>

<?php $form = ActiveForm::begin([
    'id' => 'question-form',
]); ?>
<?= $form->errorSummary($model); ?>

<?= $form->field($model, 'title')
    ->textInput()
    ->hint(Yii::t('question', "What's your question? Be specific.")); ?>

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

<?= $form->field($model, 'content')
    ->textarea(['rows' => 5])
    ->hint(Yii::t('question', 'Markdown powered content')); ?>


<div class="form-group">
    <div class="btn-group btn-group-lg">
        <?= Html::submitButton(Yii::t('question', 'Draft'), [
            'class' => 'btn',
            'name' => 'Question[status]',
            'value' => 0
        ]) ?>
        <?php if ($model->isNewRecord): ?>
            <?= Html::submitButton(Yii::t('question', 'Publish'), [
                'class' => 'btn btn-primary',
                'name' => 'Question[status]',
                'value' => 1
            ]) ?>
        <?php else: ?>
            <?= Html::submitButton(Yii::t('question', 'Update'), [
                'class' => 'btn btn-success',
                'name' => 'Question[status]',
                'value' => 1
            ]) ?>
        <?php endif; ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

