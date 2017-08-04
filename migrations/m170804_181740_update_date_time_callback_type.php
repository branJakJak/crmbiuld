<?php

use yii\db\Migration;

class m170804_181740_update_date_time_callback_type extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('tbl_cavity', 'when_property_moved', $this->dateTime());
    }

    public function safeDown()
    {
        $this->alterColumn('tbl_cavity', 'when_property_moved', $this->string());
    }

}
