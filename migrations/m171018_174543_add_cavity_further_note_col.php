<?php

use yii\db\Migration;

class m171018_174543_add_cavity_further_note_col extends Migration
{
    public function safeUp()
    {
        $this->addColumn('tbl_cavity', 'further_notes', $this->text());
    }

    public function safeDown()
    {
        $this->dropColumn('tbl_cavity', 'further_notes');
    }

}
