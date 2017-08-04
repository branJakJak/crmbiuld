<?php

use yii\db\Migration;

class m170804_170019_add_column_when_they_move_property extends Migration
{
    public function up()
    {
        $this->addColumn(
            "tbl_cavity",
            'when_property_moved' ,
            $this->date()
        );
    }

    public function down()
    {
        $this->dropColumn("tbl_cavity", 'when_property_moved');
    }
}
