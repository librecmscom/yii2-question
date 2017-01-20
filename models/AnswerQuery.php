<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\question\models;

use yii\db\ActiveQuery;

class AnswerQuery extends ActiveQuery
{
    /**
     * Apply possible answers order to query
     * @param ActiveQuery $query
     * @param $order
     * @return string
     */
    public function applyOrder(ActiveQuery $query, $order)
    {
        switch ($order) {
            case 'time':
                $query->orderBy(['created_at' => SORT_ASC]);
                break;

            case 'supports':
            default:
                $query->orderBy(['(supports / pow((((UNIX_TIMESTAMP(NOW()) - created_at) / 3600) + 2),1.8) )' => SORT_DESC]);
                break;
        }

        return $order;
    }
}