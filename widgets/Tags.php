<?php

namespace yuncms\question\widgets;

use yii\base\Widget;
use yuncms\tag\models\Tag;


/**
 * 获取热门标签
 * @author Nikolay Kostyurin <nikolay@artkost.ru>
 * @since 2.0
 */
class Tags extends Widget
{

    public $limit = 20;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $models = Tag::find()
            ->orderBy(['frequency' => SORT_DESC])
            ->limit($this->limit)
            ->all();

        return $this->render('tags', [
            'models' => $models,
        ]);
    }
}
