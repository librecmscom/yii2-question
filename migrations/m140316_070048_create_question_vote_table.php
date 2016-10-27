<?php
use yii\db\Migration;
use yuncms\question\models\Vote;

class m140316_070048_create_question_vote_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(Vote::tableName(), [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'entity_id' => $this->integer()->notNull(),
            'entity' => $this->smallInteger()->notNull(),
            'vote' => $this->smallInteger()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_ip' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('vote_ibfk_1', Vote::tableName(), 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable(Vote::tableName());
    }
}
