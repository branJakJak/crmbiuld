<?php
/**
 * @var $cavityModel \app\models\Cavity
 */
use derekisbusy\panel\PanelWidget;
use yii\helpers\Html;
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
            <?php if (is_null($cavityModel) || empty($cavityModel)): ?>
                No other information provided. These lead is created within crmbuild. Please re-enter details at <a
                        href="http://<?= Yii::$app->params['crm_lead_url'] ?>">http://<?= Yii::$app->params['crm_lead_url'] ?></a>
            <?php endif ?>
            <?php if ($cavityModel): ?>
                <?php
                echo PanelWidget::begin([
                    'title' => "{$cavityModel->title} {$cavityModel->firstname} {$cavityModel->lastname}",
                    'type' => 'default',
                    'widget' => false,
                ])
                ?>
                <?php $form = \yii\widgets\ActiveForm::begin() ?>
                <h3>Submitted on : <b><?= $cavityModel->date_created ?></b></h3>
                <hr>
                <?= $form->field($cavityModel, 'address1_cavity_installation') ?>
                <?= $form->field($cavityModel, 'address2_cavity_installation') ?>
                <?= $form->field($cavityModel, 'address3_cavity_installation') ?>
                <?= $form->field($cavityModel, 'address_postcode_cavity_installation'); ?>
                <?= $form->field($cavityModel, 'address_town_cavity_installation'); ?>
                <?= $form->field($cavityModel, 'address_country_cavity_installation'); ?>
                <label for="">CWI Installation Date</label>
                <?=
                \kartik\date\DatePicker::widget([
                    'model' => $cavityModel,
                    'attribute' => 'CWI_installation_date',
                ])
                ?>
                <br>

                <?= $form->field($cavityModel, 'CWI_installer'); ?>
                <?= $form->field($cavityModel, 'construction_type'); ?>
                <?= $form->field($cavityModel, 'property_exposure'); ?>
                <?= $form->field($cavityModel, 'CWI_payment'); ?>
                <label for="">After CWI Installation Date</label>
                <?=
                \kartik\date\DatePicker::widget([
                    'model' => $cavityModel,
                    'attribute' => 'after_CWI_installation_date',
                ])
                ?>
                <br>
                <?= $form->field($cavityModel, 'suffered_issues_prior_to_CWI'); ?>
                <?= $form->field($cavityModel, 'work_to_rectify_CWI'); ?>
                <?= $form->field($cavityModel, 'incured_financial_expenses'); ?>
                <?= $form->field($cavityModel, 'document_copy'); ?>
                <?= $form->field($cavityModel, 'reported_issue_to_house_insurer'); ?>
                <?= $form->field($cavityModel, 'advice_about_suitability'); ?>
                <?= $form->field($cavityModel, 'is_in_IVA_or_Bankrupt'); ?>
                <?= $form->field($cavityModel, 'created_by_user'); ?>
                <?= $form->field($cavityModel, 'mobile_landline'); ?>
                <?= $form->field($cavityModel, 'second_application_title'); ?>
                <?= $form->field($cavityModel, 'second_application_firstname'); ?>
                <?= $form->field($cavityModel, 'second_application_lastname'); ?>

                <label for="">Birthday(second applicant)</label>
                <?=
                \kartik\date\DatePicker::widget([
                    'model' => $cavityModel,
                    'attribute' => 'second_application_birthday',
                ])
                ?>
                <br>

                <?= $form->field($cavityModel, 'second_application_telephone'); ?>
                <?= $form->field($cavityModel, 'second_application_mobile_landline'); ?>
                <?= $form->field($cavityModel, 'second_application_email_address'); ?>
                <?= $form->field($cavityModel, 'property_history'); ?>

                <?php if (!Yii::$app->user->can('Manager') && !Yii::$app->user->can('Consultant') && !Yii::$app->user->can('Agent')): ?>
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                <?php endif ?>

                <?php \yii\widgets\ActiveForm::end() ?>
                <?php
                PanelWidget::end();
                ?>
            <?php endif ?>
        </div>

    </div>


</div>