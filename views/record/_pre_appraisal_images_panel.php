<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 7/10/2017
 * Time: 8:59 PM
 */
use derekisbusy\panel\PanelWidget;
use dosamigos\fileupload\FileUploadUI;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $preappraisalImageDataProvider \yii\data\ActiveDataProvider */
/* @var $propertyRecord \app\models\PropertyRecord */
/* @var $preappraisalImage \app\models\PropertyPreAppraisalImages */

?>


<?php
echo PanelWidget::begin([
    'title'=>'Upload',
    'type'=>'default',
    'widget'=>false,
])
?>

<?php $form = \yii\widgets\ActiveForm::begin()?>
<?= FileUploadUI::widget([
    'model' => $preappraisalImage,
    'attribute' => 'image_name',
    'url' => ['/record/update', 'id' => $propertyRecord->id],
    'gallery' => false,
    'fieldOptions' => [
        'accept' => 'image/*'
    ],
    'clientOptions' => [
        'maxFileSize' => 2000000
    ],
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                                window.location.reload()
                            }',
//        'fileuploadfail' => 'function(e, data) {
//                                console.log(e);
//                                console.log(data);
//                            }',
    ],
]); ?>
<?php \yii\widgets\ActiveForm::end()?>
<br >
<?php
    echo \yii\widgets\ListView::widget([
        'dataProvider' => $preappraisalImageDataProvider,
        'itemView' => '__list_pre_appraisal_images',
    ]);
?>

<?php
PanelWidget::end()
?>