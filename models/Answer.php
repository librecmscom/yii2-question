<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\question\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\Markdown;
use yii\helpers\HtmlPurifier;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * Answer Model
 * @package yuncms\question
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $question_id
 * @property string $content
 * @property integer $votes
 * @property integer $status
 * @property integer $is_correct
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Question $question
 *
 * @author Nikolay Kostyurin <nikolay@artkost.ru>
 * @since 2.0
 */
class Answer extends ActiveRecord
{
    /**
     * 草稿
     */
    const STATUS_DRAFT = 0;

    /**
     * 发布
     */
    const STATUS_PUBLISHED = 1;

    /**
     * Markdown processed content
     * @var string
     */
    public $body;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%question_answer}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_AFTER_FIND => 'body'
                ],
                'value' => function ($event) {
                    return HtmlPurifier::process($event->sender->content);
                }
            ],
            [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['question_id'], 'exist', 'targetClass' => Question::className(), 'targetAttribute' => 'id']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('question', 'ID'),
            'content' => Yii::t('question', 'Content'),
            'status' => Yii::t('question', 'Status'),
        ];
    }

    /**
     * User Relation
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'user_id']);
    }

    /**
     * @return Question
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }

    /**
     * 是否是作者
     * @return bool
     */
    public function isAuthor()
    {
        return $this->user_id == Yii::$app->user->id;
    }

    /**
     * 检查这个答案是否正确
     * @return bool
     */
    public function isCorrect()
    {
        return $this->is_correct;
    }

    /**
     * 切换是否正确
     * @return bool
     */
    public function toggleCorrect()
    {
        $this->is_correct = !$this->isCorrect();
        return $this->save();
    }

    /**
     * @param int $question_id
     * @return int
     */
    public static function removeRelation($question_id)
    {
        return self::deleteAll(['question_id' => $question_id]);
    }

    /**
     * Apply possible answers order to query
     * @param ActiveQuery $query
     * @param $order
     * @return string
     */
    public static function applyOrder(ActiveQuery $query, $order)
    {
        switch ($order) {
            case 'oldest':
                $query->orderBy('created_at DESC');
                break;

            case 'active':
                $query->orderBy('created_at ASC');
                break;

            case 'supports':
            default:
                $query->orderBy(['votes' => SORT_DESC]);
                break;
        }

        return $order;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            Question::incrementAnswers($this->question_id);
        }
    }

    /**
     * This is invoked after the record is deleted.
     */
    public function afterDelete()
    {
        parent::afterDelete();
        Question::decrementAnswers($this->question_id);
    }
}
