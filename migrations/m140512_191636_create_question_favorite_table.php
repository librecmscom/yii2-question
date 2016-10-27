<?php
use yii\db\Migration;
use yuncms\question\models\Favorite;
use yuncms\question\models\Question;

class m140512_191636_create_question_favorite_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(Favorite::tableName(), [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'question_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_ip' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('favorite_ibfk_1', Favorite::tableName(), 'question_id', Question::tableName(), 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('favorite_ibfk_2', Favorite::tableName(), 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable(Favorite::tableName());
    }
}
