<?php

use yii\db\Migration;

class m170814_110248_add_new_column_is_in_IVA_or_bankrupt extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            'tbl_cavity', 
            'is_in_IVA_or_Bankrupt', 
            $this->string()
        );
    }

    public function safeDown()
    {
        $this->dropColumn('tbl_cavity', 'is_in_IVA_or_Bankrupt');
    }

}
