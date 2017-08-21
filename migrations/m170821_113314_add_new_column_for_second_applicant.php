<?php

use yii\db\Migration;

class m170821_113314_add_new_column_for_second_applicant extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            "tbl_cavity",
            'second_application_title',
            $this->string()
        );
        $this->addColumn(
            "tbl_cavity",
            'second_application_firstname',
            $this->string()
        );
        $this->addColumn(
            "tbl_cavity",
            'second_application_lastname',
            $this->string()
        );                

        $this->addColumn(
            "tbl_cavity",
            'second_application_birthday',
            $this->date()
        );

        $this->addColumn(
            "tbl_cavity",
            'second_application_telephone',
            $this->date()
        );
        $this->addColumn(
            "tbl_cavity",
            'second_application_mobile_landline',
            $this->date()
        );
        $this->addColumn(
            "tbl_cavity",
            'second_application_email_address',
            $this->date()
        );
    }

    public function safeDown()
    {
        $this->dropColumn("tbl_cavity", 'second_application_title');        
        $this->dropColumn("tbl_cavity", 'second_application_firstname');        
        $this->dropColumn("tbl_cavity", 'second_application_lastname');        
        $this->dropColumn("tbl_cavity", 'second_application_birthday');        
        $this->dropColumn("tbl_cavity", 'second_application_telephone');        
        $this->dropColumn("tbl_cavity", 'second_application_mobile_landline');        
        $this->dropColumn("tbl_cavity", 'second_application_email_address');        
    }
}
