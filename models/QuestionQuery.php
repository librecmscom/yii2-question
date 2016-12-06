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
            $this->orderBy(['(answers / pow((((UNIX_TIMESTAMP(NOW()) - created_at) / 3600) + 2),1.8) )' => SORT_DESC]);
        } elseif ($order == 'reward') {//悬赏问题
            $this->andWhere(['>', 'price', 0])->orderBy(['created_at' => SORT_DESC, 'views' => SORT_DESC]);
        } elseif ($order == 'unanswered') {//未回答问题
            $this->andWhere(['answers' => 0])->orderBy(['created_at' => SORT_DESC]);
        }
    }

    /**
     * 查询活动的代码
     * @return $this
     */
    public function active()
    {
        return $this->andWhere(['>','status' ,0]);
    }

    /**
     * 热门问题
     */
    public function hottest()
    {
        return $this->active()->orderBy(['views' => SORT_DESC, 'answers' => SORT_DESC, 'created_at' => SORT_DESC]);
    }

    /**
     * 最新问题
     * @return $this
     */
    public function newest()
    {
        return $this->active()->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * 未回答的
     * @return $this
     */
    public function unAnswered()
    {
        return $this->active()->andWhere(['answers' => 0])->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * 悬赏问题
     * @return $this
     */
    public function reward()
    {
        return $this->active()->andWhere(['>', 'price', 0])->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * @param $limit
     * @return static
     */
    public function views($limit)
    {
        return $this->active()->andWhere(['>', 'views', $limit]);
    }
}