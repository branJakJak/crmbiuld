<?php

use yii\db\Migration;

class m170710_192204_property_document_new_column_uploaded_by extends Migration
{
    public function safeUp()
    {
        $this->addColumn("tbl_property_documents", "uploaded_by", $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn("tbl_property_documents", "uploaded_by");
    }

}
