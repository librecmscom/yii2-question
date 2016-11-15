<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\question\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * Question Model Search
 *
 * @package yuncms\question
 */
class QuestionSearch extends Model
{

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('question', 'Question'),
            'user_id' => Yii::t('question', 'User'),
            'answers' => Yii::t('question', 'Answers'),
            'views' => Yii::t('question', 'Views'),
            'created_at' => Yii::t('question', 'Created At'),
        ];
    }

    /**
     * 首页搜索
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Question::find()->with('user');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        return $dataProvider;
    }

    /**
     * 首页排序
     * @param string $params
     * @param string $order 排序
     * @return ActiveDataProvider
     */
    public function searchOrder($params, $order)
    {
        $dataProvider = $this->search($params);
        if ($order && $order == 'new') {//按发布时间倒序
            $dataProvider->query->orderBy(['created_at' => SORT_DESC]);
        } elseif ($order && $order == 'hottest') {//热门问题
            $dataProvider->query->orderBy(['answers' => SORT_DESC, 'views' => SORT_DESC]);
        } elseif ($order && $order == 'reward') {//悬赏问题
            $dataProvider->query->orderBy(['created_at' => SORT_DESC, 'price' => SORT_DESC, 'views' => SORT_DESC]);
        } elseif ($order && $order == 'unanswered') {//未回答问题
            $dataProvider->query->andWhere(['answers' => 0]);
        }
        return $dataProvider;
    }

    /**
     * @param array $params
     * @param int $userID
     * @return ActiveDataProvider
     */
    public function searchMy($params, $userID)
    {
        $dataProvider = $this->search($params);
        $dataProvider->query->where(['user_id' => $userID]);

        return $dataProvider;
    }
}
