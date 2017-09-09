<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\question\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Markdown;
use yii\helpers\Inflector;
use yii\helpers\HtmlPurifier;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yuncms\collection\models\Collection;
use yuncms\tag\behaviors\TagBehavior;
use yuncms\tag\models\Tag;


/**
 * Question Model
 * @package artkost\qa\models
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $alias
 * @property string $content
 * @property integer $answers
 * @property integer $views
 * @property integer $votes
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property array $tags
 */
class Question extends ActiveRecord
{
    //正常
    const STATUS_ACTIVE = 1;

    //结束
    const STATUS_END = 2;


    /**
     * Markdown processed content
     * @var string
     */
    public $body;

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new QuestionQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%question}}';
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
                    ActiveRecord::EVENT_BEFORE_INSERT => 'alias'
                ],
                'value' => function ($event) {
                    return Inflector::slug($event->sender->title);
                }
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_AFTER_FIND => 'body'
                ],
                'value' => function ($event) {
                    return HtmlPurifier::process(Markdown::process($event->sender->content, 'gfm'));
                }
            ],
            'tag' => [
                'class' => TagBehavior::className(),
                'tagValuesAsArray' => false,
                'tagRelation' => 'tags',
                'tagValueAttribute' => 'name',
                'tagFrequencyAttribute' => 'frequency',
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
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
            [['title', 'content', 'tagValues'], 'required'],
            ['price', 'integer', 'min' => 0, 'max' => Yii::$app->user->identity->extend->coins, 'tooBig' => Yii::t('question', 'Insufficient points, please recharge.'),
                'tooSmall' => Yii::t('question', 'Please enter the correct points.')],
            ['hide', 'boolean'],
            ['tagValues', 'safe'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_END]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('question', 'ID'),
            'title' => Yii::t('question', 'Title'),
            'price' => Yii::t('question', 'Reward'),
            'hide' => Yii::t('question', 'Hide'),
            'alias' => Yii::t('question', 'Alias'),
            'content' => Yii::t('question', 'Content'),
            'tagValues' => Yii::t('question', 'Tags'),
            'status' => Yii::t('question', 'Status'),
            'views' => Yii::t('question', 'Views'),
            'answers' => Yii::t('question', 'Answers'),
            'followers' => Yii::t('question', 'Followers'),
            'collections' => Yii::t('question', 'Collections'),
            'comments' => Yii::t('question', 'Comments'),
            'created_at' => Yii::t('question', 'Created At'),
            'updated_at'=> Yii::t('question', 'Updated At'),
        ];
    }

    /**
     * Tag Relation
     * @return \yii\db\ActiveQueryInterface
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('{{%question_tag}}', ['question_id' => 'id']);
    }

    /**
     * Answer Relation
     * @return \yii\db\ActiveQueryInterface
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['question_id' => 'id']);
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
     * Collection Relation
     * @return \yii\db\ActiveQueryInterface
     */
    public function getCollections()
    {
        return $this->hasMany(Collection::className(), ['model_id' => 'id'])->onCondition(['model' => static::className()]);
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
     * 是否匿名
     * @return bool
     */
    public function isHide()
    {
        return $this->hide == 1;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            /*悬赏提问*/
            if ($this->price > 0) {
                credit($this->user_id, 'ask', -$this->price, $this->id, $this->title);
            }
            //记录动态
            doing($this->user_id, 'ask', get_class($this), $this->id, $this->title, mb_substr(strip_tags($this->content), 0, 200));
            /* 用户提问数+1 */
            Yii::$app->user->identity->extend->updateCounters(['questions' => 1]);
        }
    }

    /**
     * This is invoked after the record is deleted.
     */
    public function afterDelete()
    {
        $this->user->extend->updateCounters(['questions' => -1]);
        Answer::deleteAll(['question_id' => $this->id]);
        parent::afterDelete();
    }
}
