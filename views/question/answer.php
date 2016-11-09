<?php

use Leaps\Helper\Html;
use Leaps\Widget\ActiveForm;

/** @var ActiveForm $form */
$form = ActiveForm::begin(['id' => 'answer-form']);
?>

<?= $form->errorSummary($model); ?>

<?= $form->field($model, 'content')
    ->textarea(['rows' => 6])
    ->hint(Leaps::t('question', 'Markdown powered content'))
    ->label(''); ?>

<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Leaps::t('question', 'Answer') : Leaps::t('question', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>
