<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var ActiveForm $form */
$form = ActiveForm::begin(['id' => 'answer-form']);
?>

<?= $form->errorSummary($model); ?>

<?= $form->field($model, 'content')
    ->textarea(['rows' => 6])
    ->hint(Yii::t('question', 'Markdown powered content'))
    ->label(false); ?>

<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('question', 'Answer') : Yii::t('question', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>
