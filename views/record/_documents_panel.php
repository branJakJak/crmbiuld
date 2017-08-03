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
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $propertyRecord \app\models\PropertyRecord */
/* @var $propertyDocument \app\models\PropertyDocuments */

?>
<style type="text/css">
    #w9 > div.panel-heading {
        padding: 23px 20px;
    }
</style>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <?php
            $downloadAllBtn = Html::a("Download All", \yii\helpers\Url::to(["/record/download-document", "record_id" => $propertyRecord->id]), ['class' => 'pull-right btn btn-success']);
            echo PanelWidget::begin([
                'title' => 'File Upload '.$downloadAllBtn,
                'type' => 'default',
                'widget' => false,
            ]);
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
                                        $.pjax.reload({container:"#propertyDocumentsPjax"});
                                    }',
        //        'fileuploadfail' => 'function(e, data) {
        //                                console.log(e);
        //                                console.log(data);
        //                            }',
            ],
        ]); ?>



        <?php \yii\widgets\ActiveForm::end()?>

        <br >
        <?php Pjax::begin(['id' => 'propertyDocumentsPjax','timeout'=>10000]) ?>
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
        <?php Pjax::end() ?>


        <?php
        PanelWidget::end()
        ?>
    </div>
</div>