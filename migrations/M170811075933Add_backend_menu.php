<?php

namespace yuncms\question\migrations;

use yii\db\Migration;

/**
 * Class M170811075933Add_backend_menu
 */
class M170811075933Add_backend_menu extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('{{%admin_menu}}', [
            'name' => '问答管理',
            'parent' => 8,
            'route' => '/question/question/index',
            'icon' => 'fa-question',
            'sort' => NULL,
            'data' => NULL
        ]);

        $id = (new \yii\db\Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '问答管理', 'parent' => 8,])->scalar($this->getDb());
        $this->batchInsert('{{%admin_menu}}', ['name', 'parent', 'route', 'visible', 'sort'], [
            ['问题查看', $id, '/question/question/view', 0, NULL],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $id = (new \yii\db\Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '问答管理', 'parent' => 8,])->scalar($this->getDb());
        $this->delete('{{%admin_menu}}', ['parent' => $id]);
        $this->delete('{{%admin_menu}}', ['id' => $id]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M170811075933Add_backend_menu cannot be reverted.\n";

        return false;
    }
    */
}
