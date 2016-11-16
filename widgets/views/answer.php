<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use xutl\summernote\SummerNote;
?>
<?php if (Yii::$app->getUser()->getIsGuest()): ?>
    请登录
<?php else: ?>
    <div id="answerSubmit" class="mt-15 clearfix">
        <?php
        /** @var ActiveForm $form */
        $form = ActiveForm::begin([
            'id' => 'answer_form',
            'options' => ['class' => 'editor-wrap'],
            'action' => Url::to(['/question/answer/create']),
        ]);
        ?>
        <?= Html::hiddenInput('question_id', $question_id) ?>
        <div class="editor" id="questionText">
        <?= $form->field($model, 'content')->widget(SummerNote::className())->label(false); ?>

        </div>
        <div id="answerSubmit" class="mt-15 clearfix">
<!--            <div class="checkbox pull-left">-->
<!--                <label><input type="checkbox" id="attendTo" name="followed" value="1" checked/>关注该问题</label>-->
<!--            </div>-->
            <div class="pull-right">
                <?= Html::submitButton(Yii::t('question', 'Answer'), ['class' => 'btn btn-primary pull-right']) ?>
            </div>
        </div>


        <?php ActiveForm::end() ?>
    </div>
<?php endif ?>
