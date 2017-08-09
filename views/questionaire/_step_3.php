<?php
use derekisbusy\panel\PanelWidget;
use dosamigos\fileupload\FileUploadUI;

?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php
            echo PanelWidget::begin([
                'title' => 'Upload External Images',
                'type' => 'default',
                'widget' => false,
            ])
        ?>    
        <?=
            FileUploadUI::widget([
                'model' => $supportingDocument,
                'attribute' => 'document_name',
                'url' => [
                    '/cavity/update',
                    'id' => $cavity_model->id,
                    'type'=>\app\models\CavitySupportingDocument::FILE_TYPE_GUARANTOR_CERTIFICATE,
                    'cavity_form_id'=>$cavity_model->id
                ],
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
                                        }',
                ],
                'options'=>[
                    'id' => 'guarantor_certificate'
                ]
            ]); 
        ?>


        <?php
            PanelWidget::end()
        ?>



    </div>
</div>
