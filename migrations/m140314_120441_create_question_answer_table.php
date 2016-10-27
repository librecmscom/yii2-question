<?php
use yii\db\Migration;
use yuncms\question\models\Answer;
use yuncms\question\models\Question;

/**
 * Class m140314_120441_create_question_answer_table
 */
class m140314_120441_create_question_answer_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(Answer::tableName(), [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'question_id' => $this->integer()->notNull(),
            'content' => $this->text()->notNull(),
            'is_correct' => $this->integer()->notNull()->defaultValue(0),
            'votes' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('answer_ibfk_1', Answer::tableName(), 'question_id', Question::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('answer_ibfk_2', Answer::tableName(), 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropTable(Answer::tableName());
    }
}
