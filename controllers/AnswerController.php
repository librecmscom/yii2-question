<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\question\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yuncms\question\models\Question;
use yuncms\question\models\Answer;

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
                        'actions' => ['adopt'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 采纳回答
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionAdopt()
    {
        $answerId = Yii::$app->request->post('answerId');
        $answer = Answer::findOne($answerId);
        if (!$answer) {
            throw new NotFoundHttpException ();
        }
//        if (Yii::$app->user->id !== $answer->question->user_id) {
//            Yii::$app->session->setFlash('danger', Yii::t('question', 'You can not take your own answer.'));
//            return $this->redirect(['/question/question/view','id'=>$answer->question_id]);
//        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $answer->adopted_at = time();
            $answer->save();
            $answer->question->status = Question::STATUS_END;
            $answer->question->save();

            if (isset(Yii::$app->user->identity->userData->adoptions)) {
                Yii::$app->user->identity->userData->updateCounters(['adoptions' => 1]);
            }
            /*悬赏处理*/
            if ($answer->question->price > 0) {
                Yii::$app->getModule('user')->point($answer->user_id, $answer->question->price, 'answer_adopted', get_class($answer->question), $answer->question->id, $answer->question->title);
            }
            $transaction->commit();
            Yii::$app->session->setFlash('success', Yii::t('question', 'Answer to adopt success.'));
            return $this->redirect(['/question/question/view','id'=>$answer->question_id]);
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('danger', Yii::t('question', 'Answer failed. Please try again later.'));
            return $this->redirect(['/question/question/view','id'=>$answer->question_id]);
        }
    }
}