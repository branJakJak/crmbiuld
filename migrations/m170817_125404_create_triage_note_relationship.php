<?php

use yii\db\Migration;

class m170817_125404_create_triage_note_relationship extends Migration
{
    public function safeUp()
    {
        $this->addForeignKey(
            'triage_note_relationship',
            'tbl_triage_note',
            'triage_id',
            'tbl_triage',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }
    public function safeDown()
    {
        $this->dropForeignKey(
            'triage_note_relationship',
            'tbl_triage_note'
        );
    }
}
