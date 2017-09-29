<?php

use yii\db\Migration;

class m170821_113255_add_column_landline extends Migration
{
    public function safeUp()
    {

        $this->addColumn(
            "tbl_cavity",
            'mobile_landline' ,
            $this->string()
        );
    }
    public function safeDown()
    {
        $this->dropColumn("tbl_cavity", 'mobile_landline');
    }
}