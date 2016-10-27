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
        $query->andWhere(['status' => Question::STATUS_PUBLISHED]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        return $dataProvider;
    }

    /**
     * 搜索结果排序
     * @param string $params
     * @param string $order 排序
     * @return ActiveDataProvider
     */
    public function searchOrder($params, $order)
    {
        $dataProvider = $this->search($params);
        if ($order && $order == 'new') {
            $dataProvider->query->orderBy(['created_at' => SORT_DESC]);
        } elseif ($order && $order == 'new') {
            $dataProvider->query->orderBy(['answers' => SORT_DESC, 'views' => SORT_DESC]);
        } elseif ($order && $order == 'unanswered') {
            $dataProvider->query->andWhere(['answers' => 0]);
        }
        return $dataProvider;
    }

    /**
     * @param array $params
     * @param int $userID
     * @return ActiveDataProvider
     */
    public function searchFavorite($params, $userID)
    {
        $dataProvider = $this->search($params);
        $dataProvider->query
            ->joinWith('favorites', true, 'RIGHT JOIN')
            ->andWhere([Favorite::tableName() . '.user_id' => $userID]);

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
        $dataProvider->query
            ->andWhere(['status' => Question::STATUS_DRAFT])
            ->where(['user_id' => $userID]);

        return $dataProvider;
    }

    /**
     * @param \yii\db\ActiveRecord $query
     * @param string $attribute
     * @param bool $partialMatch
     */
    protected function addCondition($query, $attribute, $partialMatch = false)
    {
        if (($pos = strrpos($attribute, '.')) !== false) {
            $modelAttribute = substr($attribute, $pos + 1);
        } else {
            $modelAttribute = $attribute;
        }

        $value = $this->$modelAttribute;
        if (trim($value) === '') {
            return;
        }
        if ($partialMatch) {
            $query->andWhere(['like', $attribute, $value]);
        } else {
            $query->andWhere([$attribute => $value]);
        }
    }
}
