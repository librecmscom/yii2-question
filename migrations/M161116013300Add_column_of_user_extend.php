<?php

namespace yuncms\question\migrations;

use yii\db\Migration;

class M161116013300Add_column_of_user_extend extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user_extend}}', 'questions', $this->integer()->unsigned()->defaultValue(0)->comment('提问数'));
        $this->addColumn('{{%user_extend}}', 'answers', $this->integer()->unsigned()->defaultValue(0)->comment('回答数'));
        $this->addColumn('{{%user_extend}}', 'adoptions', $this->integer()->unsigned()->defaultValue(0)->comment('采纳数'));
    }

    public function down()
    {
        $this->dropColumn('{{%user_extend}}', 'questions');
        $this->dropColumn('{{%user_extend}}', 'answers');
        $this->dropColumn('{{%user_extend}}', 'adoptions');
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
