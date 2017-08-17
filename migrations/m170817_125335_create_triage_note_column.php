<?php

use yii\db\Migration;

class m170817_125335_create_triage_note_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            'tbl_property_notes',
            'note_type',
            $this->string()
        );
    }

    public function safeDown()
    {
        $this->dropColumn('tbl_property_notes', 'note_type');
    }

}
