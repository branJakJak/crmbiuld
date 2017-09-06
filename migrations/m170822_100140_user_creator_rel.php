<?php

use yii\db\Migration;

class m170822_100140_user_creator_rel extends Migration
{
    public function safeUp()
    {
        $this->addForeignKey(
            'agent_id_rel',
            'tbl_user_creator',
            'agent_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
            );
        $this->addForeignKey(
            'creator_id_rel',
            'tbl_user_creator',
            'creator_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('agent_id_rel', 'tbl_user_creator');
        $this->dropForeignKey('creator_id_rel', 'tbl_user_creator');
    }

}
