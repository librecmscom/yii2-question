<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use xutl\summernote\SummerNote;
/** @var ActiveForm $form */
$form = ActiveForm::begin(['id' => 'answer-form']);
?>

<?= $form->errorSummary($model); ?>

<?= $form->field($model, 'content')->widget(SummerNote::className())->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('question', 'Answer') : Yii::t('question', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end() ?>