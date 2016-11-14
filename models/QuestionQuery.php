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
     * @param $limit
     * @return static
     */
    public function views($limit)
    {
        return $this->andWhere(['>', 'views', $limit]);
    }
}