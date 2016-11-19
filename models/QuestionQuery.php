<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\question\models;

use yii\db\ActiveQuery;
use yuncms\tag\behaviors\TagQueryBehavior;

class QuestionQuery extends ActiveQuery
{

    public function behaviors()
    {
        return [
            TagQueryBehavior::className(),
        ];
    }

    /**
     * Apply possible questions order to query
     * @param string $order
     * @return string
     */
    public function applyOrder($order)
    {
        if ($order == 'new') {//按发布时间倒序
            $this->orderBy(['created_at' => SORT_DESC]);
        } elseif ($order == 'hottest') {//热门问题
            $this->orderBy(['answers' => SORT_DESC, 'views' => SORT_DESC]);
        } elseif ($order == 'reward') {//悬赏问题
            $this->orderBy(['price' => SORT_DESC, 'created_at' => SORT_DESC, 'views' => SORT_DESC]);
        } elseif ($order == 'unanswered') {//未回答问题
            $this->andWhere(['answers' => 0]);
        }
    }

    /*
     * 热门问题
     */
    public function hottest($limit = 20)
    {
        $list = $this->andWhere(['>', 'status', '0'])->orderBy(['views' => SORT_DESC, 'answers' => SORT_DESC, 'created_at' => SORT_DESC])->limit($limit);
        return $list;
    }

    /**
     * 最新问题
     */
    public function newest($limit = 20)
    {
        $list = $this->andWhere(['>', 'status', '0'])->orderBy(['created_at' => SORT_DESC])->limit($limit);
        return $list;
    }

    /**
     * 未回答的
     */
    public function unAnswered($limit = 20)
    {
        $list = $this->andWhere(['>', 'status', '0'])->andWhere(['answers' => 0])->orderBy(['created_at' => SORT_DESC])->limit($limit);
        return $list;
    }

    /**
     * 悬赏问题
     */
    public function reward($limit = 20)
    {
        $list = $this->andWhere(['>', 'status', '0'])->andWhere(['>', 'price', 0])->orderBy(['created_at' => SORT_DESC])->limit($limit);
        return $list;
    }

    /**
     * @param $limit
     * @return static
     */
    public function views($limit)
    {
        return $this->andWhere(['>', 'views', $limit]);
    }
}