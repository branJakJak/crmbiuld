<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyNotes */
/* @var $form yii\widgets\ActiveForm */
if(isset($note_type) && !empty($note_type)){
    $model->note_type = $note_type;

}
?>

<div class="property-notes-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'note_type')->hiddenInput()->label("") ?>
    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    <?php \yii\widgets\ActiveForm::end()?>

</div>
