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
use Leaps\Web\JsExpression;
use Leaps\Widget\ActiveForm;
use Leaps\Typeahead\Bloodhound;
use Leaps\Tag\Widget\TagsinputWidget;
use Leaps\Typeahead\TypeAheadPluginAsset;

/**
 * @var Leaps\Web\View $this
 */
$engine = new Bloodhound([
    'name' => 'countriesEngine',
    'clientOptions' => [
        'datumTokenizer' => new JsExpression("Bloodhound.tokenizers.obj.whitespace('name')"),
        'queryTokenizer' => new JsExpression("Bloodhound.tokenizers.whitespace"),
        'remote' => [
            'url' => Url::to(['/question/question/autocomplete', 'query' => 'QRY']),
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
    ->hint(Leaps::t('question', "What's your question? Be specific.")); ?>

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
    ->hint(Leaps::t('question', 'Markdown powered content')); ?>


<div class="form-group">
    <div class="btn-group btn-group-lg">
        <?= Html::submitButton(Leaps::t('question', 'Draft'), [
            'class' => 'btn',
            'name' => 'Question[status]',
            'value' => 0
        ]) ?>
        <?php if ($model->isNewRecord): ?>
            <?= Html::submitButton(Leaps::t('question', 'Publish'), [
                'class' => 'btn btn-primary',
                'name' => 'Question[status]',
                'value' => 1
            ]) ?>
        <?php else: ?>
            <?= Html::submitButton(Leaps::t('question', 'Update'), [
                'class' => 'btn btn-success',
                'name' => 'Question[status]',
                'value' => 1
            ]) ?>
        <?php endif; ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

