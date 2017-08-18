<?php

use yii\db\Migration;

class m170814_181302_drop_after_cwi_installation_comment extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('tbl_cavity','after_CWI_installation_comment');
    }

    public function safeDown()
    {
        $this->addColumn('tbl_cavity', 'after_CWI_installation_comment', $this->string());
    }
}
