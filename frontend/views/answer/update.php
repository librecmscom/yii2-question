<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yuncms\summernote\SummerNote;

$this->title = Yii::t('question', 'Edit Answer');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('question', 'Question & Answer'),
    'url' => ['/question/question/index']
];
$this->params['breadcrumbs'][] = [
    'label' => $model->question->title,
    'url' => ['/question/question/view', 'id' => $model->question_id]
];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin(['id' => 'answer-form']); ?>
<?= $form->field($model, 'content')->widget(SummerNote::className())->label(Yii::t('question', 'My Answer')); ?>
    <div class="row mt-20">
        <div class="col-md-12">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('question', 'Answer') : Yii::t('question', 'Confirm Changes'), ['class' => $model->isNewRecord ? 'btn btn-success  pull-right' : 'btn btn-primary  pull-right']) ?>
        </div>

    </div>
<?php ActiveForm::end() ?>