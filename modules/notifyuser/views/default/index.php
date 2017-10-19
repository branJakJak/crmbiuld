<?php
/* @var $model SettingsForm */

use derekisbusy\panel\PanelWidget;
use kartik\tabs\TabsX;

$statusNotify = [
    [
        'label' => '',
        'status' => '',
    ]
];
?>
<div class="row">
    <div class="col-md-12 col-lg-12">
        <?php
        echo PanelWidget::begin([
            'title' => 'Notification Settings',
            'type' => 'default',
            'widget' => false,
        ])
        ?>
        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'updateStatusForm']) ?>
        <legend>
            Email Address to notify
        </legend>


        <?php
        echo TabsX::widget([
            'enableStickyTabs' => true,
            'items' => [
                [
                    'label' => 'Not Submitted',
                    'content' => $form->field($model, 'new_lead_notify')->textarea(['rows' => 6])->label('New lead is arrived')->hint('One email address per line')
                ],
                [
                    'label' => 'Pending Surveyors Approval',
                    'content' => $form->field($model, 'change_lead_notify')->textarea(['rows' => 6])->label('A lead went to Pending Surveyors Approval')->hint('One email address per line')
                ],
                [
                    'label' => 'More Info',
                    'content' => $form->field($model, 'more_info')->textarea(['rows' => 6])->label('A lead changed status to More Info')->hint('One email address per line')
                ],
                [
                    'label' => 'Approved By Surveyor and Triage Complete',
                    'content' => $form->field($model, 'approved_by_surveyor_and_triage_complete')->textarea(['rows' => 6])->label('A lead changed status to Approved By Surveyor and Triage Complete')->hint('One email address per line')
                ],
                [
                    'label' => 'Land Reg Checks done, waiting CFA booking',
                    'content' => $form->field($model, 'land_reg_checks_done_waiting_CFA_booking')->textarea(['rows' => 6])->label('A lead changed status to Land Reg Checks done, waiting CFA booking')->hint('One email address per line')
                ],
                [
                    'label' => 'CFA Complete',
                    'content' => $form->field($model, 'cfa_complete')->textarea(['rows' => 6])->label('A lead changed status to CFA Complete')->hint('One email address per line')
                ],
                [
                    'label' => 'Accepted on System',
                    'content' => $form->field($model, 'system_accepted')->textarea(['rows' => 6])->label('Lead accepted in the system')->hint('One email address per line')
                ],
            ]
        ]);
        ?>
        <?= \yii\helpers\Html::submitButton("Submit", ['class' => 'btn btn-primary']) ?>
        <?php \yii\widgets\ActiveForm::end() ?>
        <?php
        PanelWidget::end()
        ?>
    </div>
</div>

