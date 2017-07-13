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
        'CWI'=>'CWI',
        'EWI'=>'EWI'
]

?>
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
            <?= $form->field($preCreatedRecord, 'town') ?>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>
        <?php
            PanelWidget::end()
        ?>
    </div>
</div>
