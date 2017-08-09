<?php

use yii\db\Migration;

class m170707_150708_create_tbl_owner extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }        
        $this->createTable('tbl_owner', [
            'id'            => $this->primaryKey(),
            'title'         => $this->string(),
            'firstname'     => $this->string(),
            'lastname'      => $this->string(),
            'company_name'  => $this->string(),
            'email_address' => $this->string(),
            'mobile_number'  => $this->string(),
            'phone_number'   => $this->string(),
            'address1'      => $this->string(),
            'address2'      => $this->string(),
            'address3'      => $this->string(),
            'postalcode'       => $this->string(),
            'town'          => $this->string(),
            'country'       => $this->string(),
            'date_created'  => $this->dateTime(),
            'date_updated'  => $this->dateTime(),
        ], $tableOptions);
    }

    public function safeDown()
    {
       $this->dropTable('tbl_owner');
    }

}
