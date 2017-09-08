<?php
/**
 * @var $cavityModel \app\models\Cavity
 */
use derekisbusy\panel\PanelWidget;
use yii\widgets\DetailView;


$imageCollection =[];
$allSupportingDocuments = $cavityModel->getSupportingDocuments()->all();
foreach ($allSupportingDocuments as $currentSupportingDocument) {
    $imageToPublish = Yii::getAlias("@supporting_document_path") . DIRECTORY_SEPARATOR . $currentSupportingDocument->document_name;
    $published = $this->assetManager->publish($imageToPublish);
    $imageCollection[] = $published[1];
}


?>


<div class="row">
    <div class="col-lg-12">
        <div class="cavity-view">
            <?php
            echo PanelWidget::begin([
                'title' => "{$cavityModel->title}. {$cavityModel->firstname} {$cavityModel->lastname}",
                'type' => 'default',
                'widget' => false,
            ])
            ?>
            <?= DetailView::widget([
                'model' => $cavityModel,
                'attributes' => [
//                    'id',
//                    'title',
//                    'firstname',
//                    'lastname',
//                    'birthday:date',
//                    'telephone_number',
//                    'email_address:email',
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
                    'second_application_email_address',
//                    'date_time_callback:datetime',
                    'date_created:datetime',
//                    'date_updated:date',
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
            'title' => 'Uploaded Images',
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