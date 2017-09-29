<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Not Submitted';
$this->params['breadcrumbs'][] = $this->title;


$gridCols = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'label' => 'Name',
        'attribute' => 'id',
        'value' => function ($model) {
            $fullname = sprintf('%s %s %s', $model->title, $model->firstname, $model->lastname);
            return Html::a($fullname, '/not-submitted/' . $model->id);

        },
        'format' => 'raw'
    ],
    // 'id',
//    'title',
//    'firstname',
//    'lastname',
    'created_by_user',
    'birthday:date',
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
    // ['class' => 'yii\grid\ActionColumn'],
];
if (!Yii::$app->user->can('Manager') && !Yii::$app->user->can('Agent') && !Yii::$app->user->can('Consultant')) {
    $gridCols[] =
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',//{view}{update}
            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>',
                        Url::to(['cavity/delete', 'id' => $model->id]),
                        [
                            'class' => 'btn',
                            'style' => 'color: red !important',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]);
                }
            ],
        ];
}
?>

<h1>The following leads needs to be scrutinized</h1>
<div class="cavity-index">
    <?php if (\Yii::$app->user->can("admin")): ?>
        <p class='hidden'>
            <?= Html::a('New record', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridCols
    ]); ?>
</div>
