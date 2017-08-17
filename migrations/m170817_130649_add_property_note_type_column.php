<?php

use yii\db\Migration;

class m170817_130649_add_property_note_type_column extends Migration
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
