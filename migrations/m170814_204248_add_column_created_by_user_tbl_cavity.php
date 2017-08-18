<?php

use yii\db\Migration;

class m170814_204248_add_column_created_by_user_tbl_cavity extends Migration
{
    public function safeUp()
    {
        $this->addColumn('tbl_cavity', 'created_by_user', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn('tbl_cavity', 'created_by_user');
        
    }

}
