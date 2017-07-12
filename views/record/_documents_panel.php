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
/* @var $propertyRecord \app\models\PropertyRecord */
/* @var $propertyDocument \app\models\PropertyDocuments */

?>

<?php
    echo PanelWidget::begin([
        'title'=>'File Upload',
        'type'=>'default',
        'widget'=>false,
    ])
?>
<?php $form = \yii\widgets\ActiveForm::begin()?>

<?= FileUploadUI::widget([
    'model' => $propertyDocument,
    'attribute' => 'document_name',
    'url' => ['/record/update', 'id' => $propertyRecord->id],
    'gallery' => false,
    'fieldOptions' => [
//        'accept' => '.doc,.docx'
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
<?=
\yii\grid\GridView::widget([
    'dataProvider' => $propertyDocumentDataProvider,
    'columns' => [
        'document_name',
        'date_created:date',
        [
            'label'=>' ',
            'value'=>function($currentModel){
                return Html::a("Download", ['/property-documents/download','property'=>$currentModel->id]);
            },
            'attribute'=>'id',
            'format'=>'html'
        ],
        [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', '/property-documents/delete?id='.$model->id, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-pjax'=>'w0',
                        ]);
                    }
                ]
        ],


    ],
]);
?>


<?php
PanelWidget::end()
?>