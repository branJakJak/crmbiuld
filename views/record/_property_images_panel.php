<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 7/10/2017
 * Time: 8:59 PM
 */
use derekisbusy\panel\PanelWidget;
use yii\helpers\Html;

/* @var $this yii\web\View */
?>

<?php
echo PanelWidget::begin([
    'title'=>'Property Images',
    'type'=>'default',
    'widget'=>false,
])
?>

<?php $form = \yii\widgets\ActiveForm::begin()?>
<?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>


<?php \yii\widgets\ActiveForm::end()?>
<?php
PanelWidget::end()
?>