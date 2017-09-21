<?php
/**
 * @var $cavityModel \app\models\Cavity
 */
use derekisbusy\panel\PanelWidget;
use yii\widgets\DetailView;

//
//$imageCollection =[];
//$allSupportingDocuments = $cavityModel->getSupportingDocuments()->all();
//foreach ($allSupportingDocuments as $currentSupportingDocument) {
//    $imageToPublish = Yii::getAlias("@supporting_document_path") . DIRECTORY_SEPARATOR . $currentSupportingDocument->document_name;
//    $published = $this->assetManager->publish($imageToPublish);
//    $imageCollection[] = $published[1];
//}


?>


<div class="row">
    <div class="col-lg-12">
        <div class="cavity-view">
            <?php if ($cavityModel): ?>
                
            
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
                    'property_history',
                    'date_created:datetime',
//                    'date_updated:date',
                ],
            ]) ?>
            <?php
                PanelWidget::end();
            ?>
            <?php endif ?>
        </div>

    </div>


</div>