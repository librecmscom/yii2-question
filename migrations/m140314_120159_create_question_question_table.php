<?php
use yii\db\Migration;
use yuncms\question\models\Question;

/**
 * Class m140314_120159_create_question_question_table
 */
class m140314_120159_create_question_question_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(Question::tableName(), [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'title' => $this->string(100)->notNull(),
            'alias' => $this->string(100)->notNull(),
            'content' => $this->text()->notNull(),
            'answers' => $this->integer()->notNull()->defaultValue(0),
            'views' => $this->integer()->notNull()->defaultValue(0),
            'votes' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('question_ibfk_1', Question::tableName(), 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable(Question::tableName());
    }
}
