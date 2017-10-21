<?php

namespace yuncms\question\migrations;

use yii\db\Migration;

class M161111091655Create_question_tag_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%question_tag}}', [
            'question_id' => $this->integer()->unsigned()->notNull()->comment('Question ID'),
            'tag_id' => $this->integer()->unsigned()->notNull()->comment('Tag ID'),
        ], $tableOptions);
        $this->addPrimaryKey('', '{{%question_tag}}', ['question_id', 'tag_id']);
        $this->addForeignKey('question_tag_ibfk_1', '{{%question_tag}}', 'question_id', '{{%question}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('question_tag_ibfk_2', '{{%question_tag}}', 'tag_id', '{{%tag}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%question_tag}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
