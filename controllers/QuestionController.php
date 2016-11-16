<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\question\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yuncms\question\models\Vote;
use yuncms\question\models\Answer;
use yuncms\question\models\Question;
use yuncms\question\models\QuestionSearch;

/**
 * Class QuestionController
 *
 * @package yuncms\question
 */
class QuestionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'favorite' => ['post'],
                    'vote' => ['post'],
                    'answer-vote' => ['post'],
                    'answer-correct' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'tag', 'auto-complete'],
                        'roles' => ['@', '?']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'answer', 'answer-update', 'delete', 'favorite', 'answer-vote', 'vote', 'favorite', 'answer-correct'],
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'auto-complete' => [
                'class' => 'yuncms\tag\actions\AutoCompleteAction',
                'clientIdGetParamName' => 'query'
            ]
        ];
    }

    /**
     * 问题首页
     *
     * @param string $order 排序类型
     * @return string
     */
    public function actionIndex()
    {
        $query = Question::find()->with('user');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $order = Yii::$app->request->get('order', 'new');

        if ($order && $order == 'new') {//按发布时间倒序
            $query->orderBy(['created_at' => SORT_DESC]);
        } elseif ($order && $order == 'hottest') {//热门问题
            $query->orderBy(['answers' => SORT_DESC, 'views' => SORT_DESC]);
        } elseif ($order && $order == 'reward') {//悬赏问题
            $query->orderBy(['created_at' => SORT_DESC, 'price' => SORT_DESC, 'views' => SORT_DESC]);
        } elseif ($order && $order == 'unanswered') {//未回答问题
            $query->andWhere(['answers' => 0]);
        }

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }
    
    /**
     * 提问
     *
     * @return \yii\web\Response|string
     */
    public function actionCreate()
    {
        $model = new Question();
        if ($model->load(Yii::$app->request->post()) && $model->save() != null) {
            Yii::$app->session->setFlash('question Submitted');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * 修改问题
     *
     * @param integer $id
     * @return \yii\web\Response|string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        /** @var Question $model */
        $model = $this->findModel($id);
        if ($model->isAuthor()) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('question Submitted');
                return $this->redirect(['view', 'id' => $model->id]);
            }
            return $this->render('update', ['model' => $model]);
        }
        throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
    }

    /**
     * 查看问题
     *
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        /** @var Question $model */
        $model = $this->findModel($id);

        /*问题查看数+1*/
        if (!$model->isAuthor()) $model->updateCounters(['views' => 1]);

        /*已解决问题*/
        $bestAnswer = null;
        if ($model->status === Question::STATUS_END) {
            $bestAnswer = $model->getAnswers()->where(['>', 'adopted_at' , '0'])->one();
        }
        /** @var Answer $query 回答列表 */
        $query = $model->getAnswers()->with('user');

        $answerOrder = Answer::applyOrder($query, Yii::$app->request->get('answers', 'supports'));

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('view', [
            'model' => $model,
            'answerOrder' => $answerOrder,
            'bestAnswer' => $bestAnswer,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * 删除问题
     *
     * @param integer $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->isAuthor() && $model->delete()) {
            return $this->redirect(['index']);
        }
        throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
    }

    /**
     * 获取模型
     *
     * @param integer $id
     * @return Question
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        if (($model = Question::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException (Yii::t('yii', 'The requested page does not exist.'));
        }
    }
}