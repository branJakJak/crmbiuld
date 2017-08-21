<?php

use derekisbusy\panel\PanelWidget;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Cavity */
/* @var $currentSupportingDocument \app\models\CavitySupportingDocument */

$this->title = "View Information";
$this->params['breadcrumbs'][] = ['label' => 'Cavities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$tooltip = <<<EOL
$('[data-toggle="popover"]').popover();
$('[data-toggle="tooltip"]').tooltip();
EOL;

$this->registerJs($tooltip);
$imageCollection =[];
$allSupportingDocuments = $model->getSupportingDocuments()->all();
foreach ($allSupportingDocuments as $currentSupportingDocument) {
    $imageToPublish = Yii::getAlias("@supporting_document_path") . DIRECTORY_SEPARATOR . $currentSupportingDocument->document_name;
    $published = $this->assetManager->publish($imageToPublish);
    $imageCollection[] = $published[1];
}





?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1><?= Html::encode($this->title) ?></h1>
        <p class="hidden">
            <?= Html::a('Update record', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete record', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
        <?= Html::a('<i class="fa  fa-check-circle "></i> Accept', ['accept', 'id' => $model->id], ['class' => 'btn btn-success  btn-lg']) ?>
        <?= Html::a('<i class="fa  fa-delete-circle "></i> Decline', ['decline', 'id' => $model->id], ['class' => 'btn btn-danger  pull-right btn-lg']) ?>
    </div>
</div>
<br >
<div class="row">
    <div class="col-lg-12">
        <div class="cavity-view">
            <?php
                echo PanelWidget::begin([
                    'title' => 'Personal Information',
                    'type' => 'default',
                    'widget' => false,
                ])
            ?>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'title',
                    'firstname',
                    'lastname',
                    'birthday:date',
                    'telephone_number',
                    'email_address:email',
                    'address1_cavity_installation',
                    'address2_cavity_installation',
                    'address3_cavity_installation',
                    'address_postcode_cavity_installation',
                    'address_town_cavity_installation',
                    'address_country_cavity_installation',
                    'CWI_installation_date:date',
                    'CWI_installer',
                    'construction_type',
                    'property_exposure',
                    'CWI_payment',
                    'after_CWI_installation_date:date',
                    'suffered_issues_prior_to_CWI',
                    'work_to_rectify_CWI',
                    'incured_financial_expenses',
                    'document_copy',
                    'reported_issue_to_house_insurer',
                    'advice_about_suitability',
                    'is_in_IVA_or_Bankrupt',
                    'created_by_user',
                    'mobile_landline',
                    'second_application_title',
                    'second_application_firstname',
                    'second_application_lastname',
                    'second_application_birthday',
                    'second_application_telephone',
                    'second_application_mobile_landline',
                    'date_time_callback:datetime',
                    'date_created:date',
                    'date_updated:date',
                ],
            ]) ?>
            <?php
                PanelWidget::end()
            ?>
        </div>

    </div>
    <div class="col-lg-12">
        <?php
        echo PanelWidget::begin([
            'title' => 'Images',
            'type' => 'default',
            'widget' => false,
        ])
        ?>

        <?= dosamigos\gallery\Gallery::widget(['items' => $imageCollection]);?>


        <?php
//         GridView::widget([
//             'dataProvider' => $dataProvider,
//             'columns' => [
// //                ['class' => 'yii\grid\SerialColumn'],
// //                'id',
// //                'document_name',
//                 [
//                     'label'=>'Document',
//                     'value'=>function($model){
//                         $mes = 'Not Specified';
//                         if($model->type === \app\models\CavitySupportingDocument::FILE_TYPE_GUARANTOR_CERTIFICATE){
//                             $mes = 'Guarantee cert or communication confirming which company installed the cavity showing date when installed.';
//                         } else if($model->type === \app\models\CavitySupportingDocument::FILE_TYPE_PHOTO){
//                             $mes = 'Photo ID ie driving licence or passport';
//                         } else if($model->type === \app\models\CavitySupportingDocument::FILE_TYPE_PROOF_OF_ADDRESS){
//                             $mes = 'Proof of address';
//                         } else if ($model->type === 'internal_images' ){
//                             $mes = 'Proof of address';
//                         } else if ($model->type === 'external_images' ){
//                             $mes = 'External Images';
//                         } else if ($model->type === 'supporting_document_images' ){
//                             $mes = 'Supporting Document';
//                         }

//                         $htmlOptions = [
//                             'data-toggle'=>"tooltip",
//                             'title'=>$mes,
//                         ];
//                         return Html::a($model->document_name, \yii\helpers\Url::to(['cavity-supporting-document/download', 'id' => $model->id]) , $htmlOptions);
//                     },
//                     'format'=>'raw',
//                     'attribute'=>'document_name'
//                 ],

// //                'type',
// //                'document_name',
// //                'date_created',
// //                ['class' => 'yii\grid\ActionColumn'],
//             ],
//         ]); 
        ?>

        <?php
        PanelWidget::end()
        ?>

    </div>
</div>
