<?php

namespace yuncms\question\migrations;

use yii\db\Migration;

class M161111090834Create_question_vote_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%question_vote}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'entity_id' => $this->integer()->notNull(),
            'entity' => $this->smallInteger()->notNull(),
            'vote' => $this->smallInteger()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_ip' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('vote_ibfk_1', '{{%question_vote}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%question_vote}}');
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
