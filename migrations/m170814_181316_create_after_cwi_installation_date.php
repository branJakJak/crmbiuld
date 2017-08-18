<?php

use yii\db\Migration;

class m170814_181316_create_after_cwi_installation_date extends Migration
{
    public function safeUp()
    {
        $this->addColumn('tbl_cavity', 'after_CWI_installation_date', $this->date());
    }

    public function safeDown()
    {
        $this->dropColumn('tbl_cavity', 'after_CWI_installation_date');
    }

}
