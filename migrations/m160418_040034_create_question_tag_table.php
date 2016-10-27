<?php
use yii\db\Migration;
use yuncms\tag\models\Tag;
use yuncms\question\models\Question;

class m160418_040034_create_question_tag_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%question_tag}}', [
            'question_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('', '{{%question_tag}}', ['question_id', 'tag_id']);
        $this->addForeignKey('question_tag_ibfk_1', '{{%question_tag}}', 'question_id', Question::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('question_tag_ibfk_2', '{{%question_tag}}', 'tag_id', Tag::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%question_tag}}');
    }
}
