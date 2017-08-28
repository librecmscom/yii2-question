<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\question\frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yuncms\question\models\Question;
use yuncms\question\models\Answer;
use xutl\summernote\SummerNoteAction;

/**
 * Class AnswerController
 * @package yuncms\question\controllers
 */
class AnswerController extends Controller
{

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'adopt' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'adopt', 'sn-upload'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 提交回答
     * @return Response|string
     */
    public function actionCreate()
    {
        $id = Yii::$app->request->post('question_id');
        if (($question = Question::findOne($id)) == null) {
            return $this->goBack();
        }
        $model = new Answer(['question_id' => $question->id]);
        if ($model->load(Yii::$app->request->post()) && $model->save() != null) {
            Yii::$app->session->setFlash('success', Yii::t('question', 'Operation completed.'));
            return $this->redirect(['/question/question/view', 'id' => $id]);
        }
        return $this->render('create', ['model' => $model, 'question' => $question]);
    }

    /**
     * 修改回答
     * @param int $id 回答ID
     * @return Response|string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->isAuthor()) {
            if ($model->load(Yii::$app->request->post()) && $model->save() != null) {
                Yii::$app->session->setFlash('success', Yii::t('question', 'Operation completed.'));
                return $this->redirect(['/question/question/view', 'id' => $model->question_id]);
            }
            return $this->render('update', ['model' => $model]);
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('yii', 'You are not allowed to perform this action.'));
            return $this->redirect(['/question/question/view', 'id' => $model->question_id]);
        }
    }

    /**
     * 采纳回答
     * @return \yii\web\Response
     */
    public function actionAdopt()
    {
        $answerId = Yii::$app->request->post('answerId');
        $answer = $this->findModel($answerId);
        if (Yii::$app->user->id !== $answer->question->user_id) {
            Yii::$app->session->setFlash('danger', Yii::t('question', 'You can not take your own answer.'));
            return $this->redirect(['/question/question/view', 'id' => $answer->question_id]);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $answer->adopted_at = time();
            $answer->save();
            $answer->question->status = Question::STATUS_END;
            $answer->question->save(false);

            if (isset(Yii::$app->user->identity->extend->adoptions)) {
                Yii::$app->user->identity->extend->updateCounters(['adoptions' => 1]);
            }
            if (function_exists('credit')) {
                /*悬赏处理*/
                if ($answer->question->price > 0) {
                    credit($answer->user_id, 'answer_adopted', $answer->question->price, $answer->question->id, $answer->question->title);
                }
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', Yii::t('question', 'Answer to adopt success.'));
            return $this->redirect(['/question/question/view', 'id' => $answer->question_id]);
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('danger', Yii::t('question', 'Adopte failed. Please try again later.'));
            return $this->redirect(['/question/question/view', 'id' => $answer->question_id]);
        }
    }

    /**
     * 获取回答模型
     *
     * @param int $id
     * @return Answer
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        if (($model = Answer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException (Yii::t('yii', 'The requested page does not exist.'));
        }
    }
}