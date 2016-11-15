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
                'className' => 'yuncms\tag\actions\AutoCompleteAction',
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
    public function actionIndex($order = 'new')
    {
        $searchModel = new QuestionSearch();
        $dataProvider = $searchModel->searchOrder(Yii::$app->request->getQueryParams(), $order);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * 显示标签页
     *
     * @param string $tag 标签
     * @return string
     */
    public function actionTag($tag)
    {
        $query = Question::find()->anyTagValues($tag)->with('user');
        $query->andWhere(['status' => Question::STATUS_PUBLISHED]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('tag', ['tag' => $tag, 'dataProvider' => $dataProvider]);
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
            $bestAnswer = $model->getAnswers()->where(['>', 'adopted_at' => '0'])->one();
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
     * 提交回答
     * @param int $id
     * @return Response|string
     */
    public function actionAnswer($id)
    {
        $model = new Answer(['question_id' => $id]);
        /** @var Question $question */
        $question = $model->question;
        if ($model->load(Yii::$app->request->post()) && $model->save() != null) {
            Yii::$app->session->setFlash('answerFormSubmitted');
            return $this->redirect(['view', 'id' => $id]);
        }
        return $this->render('answer', ['model' => $model, 'question' => $question]);
    }

    /**
     * 修改回答
     * @param int $id 回答ID
     * @return Response|string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionAnswerUpdate($id)
    {
        $model = $this->findAnswerModel($id);
        if ($model->isAuthor()) {
            if ($model->load(Yii::$app->request->post()) && $model->save() != null) {
                Yii::$app->session->setFlash('answerFormSubmitted');
                return $this->redirect(['view', 'id' => $model->question_id]);
            }
            return $this->render('answer', ['model' => $model]);
        }
        throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
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
     * 收藏问题
     *
     * @param int $id
     * @return Response
     */
    public function actionFavorite($id)
    {
        /** @var Question $model */
        $model = Question::find()->with('favorite')->where(['id' => $id])->one();

        if ($model && $status = $model->toggleFavorite()) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'status' => true,
                'isFavorite' => $model->isFavorite(),
                'favorites' => $model->getFavoriteCount(),
            ];
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * 投票
     *
     * @param int $id
     * @param string $vote
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionVote($id, $vote)
    {
        $model = $this->findModel($id);
        if ($model && Vote::isUserCan($model, Yii::$app->user->id)) {
            Vote::process($model, $vote);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'status' => true,
                'votes' => $model->votes,
            ];
        }
        return $this->redirect(['view', 'id' => $model['id']]);
    }

    /**
     * 回答投票
     *
     * @param int $id
     * @param string $vote
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionAnswerVote($id, $vote)
    {
        $model = $this->findAnswerModel($id);
        if (Vote::isUserCan($model, Yii::$app->user->id)) {
            Vote::process($model, $vote);
            Yii::$app->response->format = Response::FORMAT_JSON;
            $data['format'] = 'json';
            return [
                'status' => true,
                'votes' => $model->votes,
            ];
        }
        return $this->redirect(['view', 'id' => $model['id']]);
    }

    /**
     * 采纳回答
     *
     * @param int $id 回答ID
     * @param int $question_id 问题ID
     * @return array|Response
     */
    public function actionAnswerCorrect($id, $question_id)
    {
        /** @var Answer $answer */
        $answer = $this->findAnswerModel($id);
        /** @var Question $question */
        $question = $this->findModel($question_id);
        if ($question->isAuthor() && $answer->question_id == $question_id) {
            $answer->toggleCorrect();
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'status' => true,
                'isCorrect' => $answer->isCorrect()
            ];
        }
        return $this->redirect(['view', 'id' => $question_id]);
    }

    /**
     * 获取回答模型
     *
     * @param int $id
     * @return Answer
     * @throws NotFoundHttpException
     */
    public function findAnswerModel($id)
    {
        if (($model = Answer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException (Yii::t('yii', 'The requested page does not exist.'));
        }
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