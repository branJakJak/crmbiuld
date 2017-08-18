<?php

use derekisbusy\panel\PanelWidget;
use dosamigos\fileupload\FileUploadUI;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $propertyRecord \app\models\PropertyRecord */
/* @var $triageDocument \app\models\Triage */
/* @var $triageDocumentDataProvider \yii\data\ActiveDataProvider */
/* @var $triageNotesDataProvider \yii\data\ActiveDataProvider */

?>
<style type="text/css">
    #w24 > div.panel-heading {
        padding: 23px 20px;
    }
</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php
            echo PanelWidget::begin([
                'title' => 'Triage Note',
                'type' => 'default',
                'widget' => false,
            ]);
        ?>
        <?= $this->render('/property-notes/_form',['model'=>$propertyNote ,'note_type'=>\app\models\PropertyNotes::NOTE_TYPE_TRIAGE ])?>
        <br>
        <?=
        \yii\grid\GridView::widget([
            'dataProvider'=>$triageNotesDataProvider,
            'columns'=>[
                'content',
                [
                    'label'=>'Created by',
                    'value'=>function($currentModel){
                        $creator = $currentModel->getCreator()->one();
                        $creatorName = '';
                        if($creator){
                            $creatorProfile = $creator->getProfile()->one();
                            $creatorName = $creatorProfile->name;
                        }
                        return $creatorName;
                    },
                ],
                'date_created:datetime',
            ]
        ])
        ?>


        <?php
            PanelWidget::end()
        ?>
    </div>
</div>



<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php
        $downloadAllBtn = Html::a("<i class='fa  fa-file-pdf-o'></i> Export PDF", \yii\helpers\Url::to(["/export/view", "id" => $propertyRecord->id]), ['class' => 'pull-right btn btn-success']);
        echo PanelWidget::begin([
            'title' => 'File Upload' . $downloadAllBtn,
            'type' => 'default',
            'widget' => false,
        ]);
        ?>

        <?php $form = \yii\widgets\ActiveForm::begin()?>
        <?=
        FileUploadUI::widget([
            'model' => $triageDocument,
            'attribute' => 'material_file_name',
            'url' => ['/record/update', 'id' => $propertyRecord->id],
            'gallery' => false,
            'fieldOptions' => [
            ],
            'clientOptions' => [
                'maxFileSize' => 2000000
            ],
            'clientEvents' => [
                'fileuploaddone' => 'function(e, data) {
                                        console.log(e);
                                        console.log(data);
                                        $.pjax.reload({container:"#triage_grid"});
                                    }',
            ],
        ]); ?>
        <?php \yii\widgets\ActiveForm::end()?>
        <br >
        <?php Pjax::begin(['id' => 'triage_grid','timeout'=>10000]) ?>
        <?=
        \yii\grid\GridView::widget([
            'dataProvider' => $triageDocumentDataProvider,
            'columns' => [
                [
                    'label' => ' ',
                    'value' => function ($currentModel) {
                        /*publish the image*/
                        $publishedImageUrl = '//placehold.it/150x150';
                        $uploadImagePath = Yii::getAlias("@upload_image_path") . DIRECTORY_SEPARATOR . $currentModel->material_file_name;
                        /*get the url of published image*/
                        $publishedImageUrl = Yii::$app->assetManager->publish($uploadImagePath);
                        return Html::a(Html::img($publishedImageUrl[1], ['style' => 'height:250px']), $publishedImageUrl[1]);
                    },
                    'attribute' => 'image_name',
                    'format' => 'html'
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