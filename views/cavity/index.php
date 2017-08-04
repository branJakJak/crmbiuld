<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'All records';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cavity-index">
    <p>
        <?= Html::a('New record', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'title',
            'firstname',
            'lastname',
            'birthday',
             'telephone_number',
             'email_address:email',
            // 'address1_cavity_installation',
            // 'address2_cavity_installation',
            // 'address3_cavity_installation',
            // 'address_postcode_cavity_installation',
            // 'address_town_cavity_installation',
            // 'address_country_cavity_installation',
            // 'CWI_installation_date',
            // 'CWI_installer',
            // 'construction_type',
            // 'property_exposure',
            // 'CWI_payment',
            // 'after_CWI_installation_comment',
            // 'suffered_issues_prior_to_CWI',
            // 'work_to_rectify_CWI',
            // 'incured_financial_expenses',
            // 'document_copy',
            // 'reported_issue_to_house_insurer',
            // 'advice_about_suitability',
            // 'date_time_callback',
            // 'date_created',
            // 'date_updated',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
