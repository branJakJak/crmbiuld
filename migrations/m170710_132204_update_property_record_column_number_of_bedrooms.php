<?php

use yii\db\Migration;

class m170710_132204_update_property_record_column_number_of_bedrooms extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('tbl_property_record', 'number_of_bedrooms', $this->integer());
    }

    public function safeDown()
    {
        $this->alterColumn('tbl_property_record', 'number_of_bedrooms', $this->string());
    }

}
