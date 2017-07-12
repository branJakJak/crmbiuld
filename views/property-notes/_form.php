<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyNotes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="property-notes-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>
    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    <?php \yii\widgets\ActiveForm::end()?>

</div>
