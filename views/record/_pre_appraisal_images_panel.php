<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 7/10/2017
 * Time: 8:59 PM
 */
use derekisbusy\panel\PanelWidget;
use dosamigos\fileupload\FileUploadUI;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $preappraisalImageDataProvider \yii\data\ActiveDataProvider */
/* @var $propertyRecord \app\models\PropertyRecord */
/* @var $preappraisalImage \app\models\PropertyPreAppraisalImages */

?>

    <style type="text/css">
        #w12 > div.panel-heading {
            padding: 23px 20px;
        }

        #w15 > table > tbody > tr > td:nth-child(1) {
            text-align: center !important;
        }

        #w15 > table > thead > tr > th:nth-child(1) {
            text-align: center !important;
        }

    </style>
    <script type="text/javascript">
        function refreshGrid(){
            $.pjax.reload({container:"#pre_appraisal_pjax"});
        }
    </script>
<?php
$downloadAllBtn = Html::a("Download All", \yii\helpers\Url::to(["/record/download-images", "record_id" => $propertyRecord->id]), ['class' => 'pull-right btn btn-success']);
echo PanelWidget::begin([
    'title' => 'Upload ' . $downloadAllBtn,
    'type' => 'default',
    'widget' => false,
])
?>


<?php $form = \yii\widgets\ActiveForm::begin(['action' => \yii\helpers\Url::to(['/record/transfer-to-triage', 'propertyId' => $propertyRecord->id])]) ?>

<?= FileUploadUI::widget([
    'model' => $preappraisalImage,
    'attribute' => 'image_name',
    'url' => ['/record/update', 'id' => $propertyRecord->id],
    'gallery' => false,
    'fieldOptions' => [
        'accept' => 'image/*'
    ],
    'clientOptions' => [
        'maxFileSize' => 100000000
    ],
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                                $.pjax.reload({container:"#pre_appraisal_pjax"});
                            }',

    ],
]); ?>
<?php \yii\widgets\ActiveForm::end() ?>
    <br>

<?php Pjax::begin(['id' => 'pre_appraisal_pjax', 'timeout' => 10000]) ?>

<?php $form = ActiveForm::begin(['action'=>\yii\helpers\Url::to(['/record/transfer-to-triage','propertyId'=>$propertyRecord->id])]); ?>
<?= Html::hiddenInput('property_record',$propertyRecord->id) ?>
<?=
\yii\grid\GridView::widget([
    'dataProvider' => $preappraisalImageDataProvider,
    'columns' => [
        [
            'class' => 'yii\grid\CheckboxColumn',
        ],
        [
            'label' => ' ',
            'value' => function ($currentModel) {
                /*publish the image*/
                if (isset($currentModel->image_name) && !empty($currentModel->image_name)) {
                    $publishedImageUrl = '';
                    $uploadImagePath = Yii::getAlias("@upload_image_path") . DIRECTORY_SEPARATOR . $currentModel->image_name;
                    if (file_exists($uploadImagePath)) {
                        /*get the url of published image*/
                        $publishedImageUrl = Yii::$app->assetManager->publish($uploadImagePath);
                        $fileType = mime_content_type($uploadImagePath);
                        if (strpos($fileType, "image") !== false) {
                            return Html::img($publishedImageUrl[1], ['style' => 'height:250px']);
                        } else {
                            $pdfSource = Url::to($publishedImageUrl[1], true);
                            $iframeTemplate = "<iframe src='https://docs.google.com/viewer?url$pdfSource=&embedded=true' frameborder='0'></iframe>";
                            return $iframeTemplate;
                        }
                    }else{
                        return "File not found";
                    }
                }
            },
            'attribute' => 'image_name',
            'format' => 'raw'
        ],
        [
            'label' => 'Image Note',
            'value' => function ($currentModel) {
                /*publish the image*/
                if (isset($currentModel->image_description) && !empty($currentModel->image_description)) {
                    return $currentModel->image_description;
                }else{
                    return $currentModel->image_name;
                }
            },
            'attribute' => 'image_description',
            'format' => 'raw'
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', '/property-pre-appraisal-images/delete?id=' . $model->id, [
                        'title' => Yii::t('yii', 'Delete'),
                        'data-pjax' => '0'
                    ]);
                }
            ]
        ]
    ],
]);
?>
<?php if (!Yii::$app->user->can('Manager') && !Yii::$app->user->can('Consultant') && !Yii::$app->user->can('Agent')): ?>
    <div>
        <?= Html::submitButton('Transfer to Triage', ['class' => 'btn btn-success']) ?>
    </div>
<?php endif ?>




<?php ActiveForm::end(); ?>

<?php Pjax::end() ?>

<?php
PanelWidget::end()
?>