<?php

use yii\db\Migration;

class m170804_141146_create_tbl_cavity extends Migration
{
    public function safeUp()
    {
        $this->createTable('tbl_cavity', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'firstname' => $this->string(),
            'lastname' => $this->string(),
            'birthday' => $this->dateTime(),
            'telephone_number' => $this->string(),
            'email_address' => $this->string(),
            'address1_cavity_installation' => $this->string(),
            'address2_cavity_installation' => $this->string(),
            'address3_cavity_installation' => $this->string(),
            'address_postcode_cavity_installation' => $this->string(),
            'address_town_cavity_installation' => $this->string(),
            'address_country_cavity_installation' => $this->string(),
            'CWI_installation_date' => $this->dateTime(),
            'CWI_installer' => $this->string(),
            'construction_type' => $this->string(),
            'property_exposure' => $this->string(),
            'CWI_payment' => $this->float(),
            'after_CWI_installation_comment' => $this->string(),
            'suffered_issues_prior_to_CWI' => $this->string(),
            'work_to_rectify_CWI' => $this->string(),
            'incured_financial_expenses' => $this->string(),
            'document_copy' => $this->string(),
            'reported_issue_to_house_insurer' => $this->string(),
            'advice_about_suitability' => $this->string(),
            'date_time_callback' => $this->string(),
            'date_created' => $this->dateTime(),
            'date_updated' => $this->dateTime()
        ]);

    }

    public function safeDown()
    {
        $this->dropTable('tbl_cavity');
    }
}