<?php

use yii\db\Migration;

class m170913_095034_add_column_property_history extends Migration
{
    public function safeUp()
    {
        $this->addColumn('tbl_cavity', 'property_history', $this->text());
    }

    public function safeDown()
    {
        $this->dropColumn('tbl_cavity', 'property_history');
    }
}