<?php
/* @var $model SettingsForm */
use derekisbusy\panel\PanelWidget;
?>
<div class="col-md-8 col-lg-8">
    <?php
    echo PanelWidget::begin([
        'title' => 'Notification Settings',
        'type' => 'default',
        'widget' => false,
    ])
    ?>
    <?php $form = \yii\widgets\ActiveForm::begin(['id'=>'updateStatusForm']) ?>
    <?= $form->field($model, 'new_lead_notify')->textarea(['rows' => 6])->label('Email address to notify when new lead went is received')->hint('One email address per line') ?>
    <?= $form->field($model, 'change_lead_notify')->textarea(['rows' => 6])->label('Email address to notify when a lead went to Pending Admin Approval ')->hint('One email address per line') ?>
    <?= \yii\helpers\Html::submitButton("Submit",['class'=>'btn btn-primary'])?>

    <?php \yii\widgets\ActiveForm::end() ?>
    <?php
        PanelWidget::end()
    ?>
</div>
