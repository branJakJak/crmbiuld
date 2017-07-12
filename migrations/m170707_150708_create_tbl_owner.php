<?php

use yii\db\Migration;

class m170707_150708_create_tbl_owner extends Migration
{
    public function safeUp()
    {
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
            'date_created'  => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'date_updated'  => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown()
    {
       $this->dropTable('tbl_owner');
    }

}
