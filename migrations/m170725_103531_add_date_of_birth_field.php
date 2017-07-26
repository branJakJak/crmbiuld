<?php

use yii\db\Migration;

class m170725_103531_add_date_of_birth_field extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            'tbl_owner',
            'date_of_birth',
            $this->date()
        );
    }

    public function safeDown()
    {
        $this->dropColumn("tbl_owner", 'date_of_birth');
    }
}
