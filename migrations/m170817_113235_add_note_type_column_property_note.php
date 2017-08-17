<?php

use yii\db\Migration;

class m170817_113235_add_note_type_column_property_note extends Migration
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
