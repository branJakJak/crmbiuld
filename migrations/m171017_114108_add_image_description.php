<?php

use yii\db\Migration;

class m171017_114108_add_image_description extends Migration
{
    public function safeUp()
    {
        $this->addColumn('tbl_property_documents', 'document_description', $this->text());
        $this->addColumn('tbl_triage', 'material_file_description', $this->text());
        $this->addColumn('tbl_cavity_supporting_document', 'document_description', $this->text());
    }

    public function safeDown()
    {
        $this->dropColumn('tbl_property_documents', 'document_description');
        $this->dropColumn('tbl_triage', 'material_file_description');
        $this->dropColumn('tbl_cavity_supporting_document', 'document_description');
    }
}
