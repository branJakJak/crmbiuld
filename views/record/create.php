<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 7/10/2017
 * Time: 6:07 PM
 */
use derekisbusy\panel\PanelWidget;
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this \yii\web\View
 */

$statusCollection = [
    \app\models\PropertyRecord::PROPERTY_STATUS_NOT_SUBMITTED=>\app\models\PropertyRecord::PROPERTY_STATUS_NOT_SUBMITTED,
    \app\models\PropertyRecord::PROPERTY_STATUS_PENDING_SUPERVISOR_APPROVAL=>\app\models\PropertyRecord::PROPERTY_STATUS_PENDING_SUPERVISOR_APPROVAL,
    \app\models\PropertyRecord::PROPERTY_STATUS_PENDING_ADMIN_APPROVAL=>\app\models\PropertyRecord::PROPERTY_STATUS_PENDING_ADMIN_APPROVAL,
    \app\models\PropertyRecord::PROPERTY_STATUS_REJECTED=>\app\models\PropertyRecord::PROPERTY_STATUS_REJECTED,
    \app\models\PropertyRecord::PROPERTY_STATUS_APPROVED=>\app\models\PropertyRecord::PROPERTY_STATUS_APPROVED,
    \app\models\PropertyRecord::PROPERTY_STATUS_WORK_IN_PROGRESS=>\app\models\PropertyRecord::PROPERTY_STATUS_WORK_IN_PROGRESS,
    \app\models\PropertyRecord::PROPERTY_STATUS_APPRAISAL_COMPLETE=>\app\models\PropertyRecord::PROPERTY_STATUS_APPRAISAL_COMPLETE,
    \app\models\PropertyRecord::PROPERTY_STATUS_APPROVED_BY_CHARTERED_SURVEYOR=>\app\models\PropertyRecord::PROPERTY_STATUS_APPROVED_BY_CHARTERED_SURVEYOR,
    \app\models\PropertyRecord::PROPERTY_STATUS_PASSED_TO_SOLICITOR=>\app\models\PropertyRecord::PROPERTY_STATUS_PASSED_TO_SOLICITOR,
    \app\models\PropertyRecord::PROPERTY_STATUS_ALL_JOBS=>\app\models\PropertyRecord::PROPERTY_STATUS_ALL_JOBS,
];
$insulationType = [
    'CWI' => 'CWI',
    'EWI' => 'EWI'
];




?>
<style type="text/css">
    #crafty_postcode_lookup_result_option1 {
        width: 100% !important;
        padding: 8px;
        margin: 10px auto;
    }
    #w1 > div.form-group.field-propertyrecord-address2,
    #w1 > div.form-group.field-propertyrecord-address3,
    #w1 > div.form-group.field-propertyrecord-country {
        display: none;
    }
</style>
<script>(function(n,t,i,r){var u,f;n[i]=n[i]||{},n[i].initial={accountCode:"HELLS11112",host:"HELLS11112.pcapredict.com"},n[i].on=n[i].on||function(){(n[i].onq=n[i].onq||[]).push(arguments)},u=t.createElement("script"),u.async=!0,u.src=r,f=t.getElementsByTagName("script")[0],f.parentNode.insertBefore(u,f)})(window,document,"pca","//HELLS11112.pcapredict.com/js/sensor.js")</script>

<div class="row">
    <div class="col-lg-6">
        <?php
            echo PanelWidget::begin([
                'title'=>'Property Details',
                'type'=>'info',
                'widget'=>false,
            ])
        ?>
        <?php $form = ActiveForm::begin(); ?>
            <?= $form
                ->field($preCreatedRecord, 'insulation_type')
                ->dropDownList($insulationType)
            ?>
            <?= $form->field($preCreatedRecord, 'postcode') ?>
            <?= $form->field($preCreatedRecord, 'address1') ?>
            <?= $form->field($preCreatedRecord, 'address2') ?>
            <?= $form->field($preCreatedRecord, 'address3') ?>
            <?= $form->field($preCreatedRecord, 'town') ?>
            <?= $form->field($preCreatedRecord, 'country')->textInput(['readonly'=>true]) ?>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>
        <?php
            PanelWidget::end()
        ?>
    </div>
</div>


