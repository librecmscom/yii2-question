<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\question\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "qa_favorite".
 *
 * @property integer $id
 * @property string $user_id
 * @property string $question_id
 * @property string $created_at
 * @property string $created_ip
 *
 * @property Question $question
 */
class Favorite extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%question_favorite}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'className' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ],
            ],
            [
                'className' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_ip'
                ],
                'value' => function ($event) {
                    $ip = ip2long(Yii::$app->request->getUserIP());
                    if ($ip > 0x7FFFFFFF) {
                        $ip -= 0x100000000;
                    }
                    return $ip;
                }
            ],
            [
                'className' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_AFTER_FIND => 'created_ip'
                ],
                'value' => function ($event) {
                    return long2ip($event->sender->created_ip);
                }
            ],
            [
                'className' => BlameableBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id'], 'required'],
            [['user_id', 'question_id', 'created_at', 'created_ip'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('question', 'ID'),
            'user_id' => Yii::t('question', 'User ID'),
            'question_id' => Yii::t('question', 'Question ID'),
            'created_at' => Yii::t('question', 'Created At'),
            'created_ip' => Yii::t('question', 'Created Ip'),
        ];
    }

    /**
     * Question Relation
     * @return \yii\db\ActiveQueryInterface
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }


    /**
     * @param int $question_id
     * @return int
     */
    public static function removeRelation($question_id)
    {
        return self::deleteAll(
            [
                'question_id' => $question_id,
            ]
        );
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function Add($id)
    {
        $favorite = new Favorite();
        $favorite->attributes = ['question_id' => $id];
        if ($favorite->save()) {
            return true;
        }
        return false;
    }

    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public static function Remove($id)
    {
        /** @var \yii\db\ActiveQuery $query */
        $query = Favorite::find()->where(['question_id' => $id, 'user_id' => Yii::$app->user->id]);
        if ($query->exists() && $query->one()->delete()) {
            return true;
        }
        return false;
    }

}
