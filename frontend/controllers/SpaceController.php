<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\question\frontend\controllers;


use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yuncms\question\models\Answer;
use yuncms\question\models\Question;
use yuncms\user\api\models\User;
use yuncms\user\models\Collection;

/**
 * Class SpaceController
 * @package yuncms\question\frontend\controllers
 */
class SpaceController extends Controller
{
    public $defaultAction = 'question';

    /**
     * 发布的问题
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionQuestion($id)
    {
        $user = $this->findModel($id);
        $query = Question::find()->where(['user_id' => $id])->orderBy(['created_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('question', [
            'user' => $user,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * 回答的问题
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAnswer($id)
    {
        $user = $this->findModel($id);
        $query = Question::find()->innerJoinWith([
            'answers' => function ($query) use ($id) {
                /** @var \yii\db\ActiveQuery $query */
                $query->where([
                    Answer::tableName() . '.user_id' => $id]);
            }
        ])->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('answer', [
            'user' => $user,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
        }
    }
}