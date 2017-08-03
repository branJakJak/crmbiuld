<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Triage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="triage-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'material_file_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'property_record')->textInput() ?>

    <?= $form->field($model, 'date_created')->textInput() ?>

    <?= $form->field($model, 'date_updated')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
