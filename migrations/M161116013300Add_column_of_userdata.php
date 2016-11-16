<?php

namespace yuncms\question\migrations;

use yii\db\Migration;

class M161116013300Add_column_of_userdata extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user_data}}', 'questions', $this->integer()->defaultValue(0));
        $this->addColumn('{{%user_data}}', 'answers', $this->integer()->defaultValue(0));
        $this->addColumn('{{%user_data}}', 'adoptions', $this->integer()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%user_data}}', 'questions');
        $this->dropColumn('{{%user_data}}', 'answers');
        $this->dropColumn('{{%user_data}}', 'adoptions');
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
