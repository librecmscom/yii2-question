<?php

namespace yuncms\question\migrations;

use yii\db\Migration;

class M161111090444Create_question_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%question}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'title' => $this->string(100)->notNull(),
            'alias' => $this->string(100)->notNull(),
            'price'=>$this->smallInteger(6)->unsigned()->defaultValue(0),
            'hide'=>$this->boolean()->defaultValue(false),
            'content' => $this->text()->notNull(),
            'answers' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'views' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'followers' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'collections' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'comments'=>$this->integer()->unsigned()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->unsigned()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('question_ibfk_1', '{{%question}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%question}}');
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
