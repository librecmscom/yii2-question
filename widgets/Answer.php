<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\question\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yuncms\question\models\Answer as AnswerModel;

/**
 * @author Xu Tongle <xutongle@gmail.com>
 */
class Answer extends Widget
{
    /** @var bool */
    public $validate = true;

    /**
     * @var integer 问题ID
     */
    public $questionId;

    /** @inheritdoc */
    public function init()
    {
        parent::init();
        if (empty ($this->questionId)) {
            throw new InvalidConfigException ('The "questionId" property must be set.');
        }
    }

    /** @inheritdoc */
    public function run()
    {
        $model = new AnswerModel();
        return $this->render('answer', [
            'model' => $model,
            'question_id' => $this->questionId,
        ]);
    }
}