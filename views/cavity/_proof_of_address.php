<?php 

/* @var \app\models\Cavity $cavity_model */
/* @var \app\models\CavitySupportingDocument $supportingDocument */
use dosamigos\fileupload\FileUploadUI;

?>
<?= FileUploadUI::widget([
    'id' => 'proof_of_address',
    'model' => $supportingDocument,
    'attribute' => 'document_name',
    'url' => [
        '/cavity/update',
        'id' => $cavity_model->id,
        'type'=>\app\models\CavitySupportingDocument::FILE_TYPE_PROOF_OF_ADDRESS,
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
        'id' => 'proof_of_address'
    ]

]); ?>
