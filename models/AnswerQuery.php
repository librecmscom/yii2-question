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
    public static function applyOrder(ActiveQuery $query, $order)
    {
        switch ($order) {
            case 'active':
                $query->orderBy('created_at ASC');
                break;

            case 'supports':
            default:
                $query->orderBy(['supports' => SORT_DESC]);
                break;
        }

        return $order;
    }
}