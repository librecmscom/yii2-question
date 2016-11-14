<?php

namespace yuncms\question\widgets;

use yii\base\Widget;
use yuncms\question\models\Question;


/**
 * 获取热门问题
 * @author Nikolay Kostyurin <nikolay@artkost.ru>
 * @since 2.0
 */
class Popular extends Widget
{
    public $limit = 10;
    public $views = 10;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $models = Question::find()
            ->views($this->views)
            ->limit($this->limit)
            ->all();

        return $this->render('popular', [
            'models' => $models,
        ]);
    }
}
