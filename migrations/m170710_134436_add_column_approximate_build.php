<?php

use yii\db\Migration;

class m170710_134436_add_column_approximate_build extends Migration
{
    public function safeUp()
    {
        $this->addColumn('tbl_property_record', 'approximate_build', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn('tbl_property_record', 'approximate_build');
    }
}
