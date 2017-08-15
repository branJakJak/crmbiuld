<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 7/10/2017
 * Time: 6:10 PM
 */
use kartik\tabs\TabsX;
use kartik\widgets\Select2;


/* @var $this yii\web\View */
/* @var $propertyRecord \app\models\PropertyRecord */
/* @var $owner \app\models\Owner */
/* @var $propertyNote \app\models\PropertyNotes */
/* @var $triageDocument \app\models\Triage */
/* @var $triageDocumentDataProvider \yii\data\ActiveDataProvider */


$statusCollection = [
    \app\models\PropertyRecord::PROPERTY_STATUS_NOT_SUBMITTED,
    \app\models\PropertyRecord::PROPERTY_STATUS_PENDING_SUPERVISOR_APPROVAL,
    \app\models\PropertyRecord::PROPERTY_STATUS_PENDING_ADMIN_APPROVAL,
    \app\models\PropertyRecord::PROPERTY_STATUS_REJECTED,
    \app\models\PropertyRecord::PROPERTY_STATUS_APPROVED,
    \app\models\PropertyRecord::PROPERTY_STATUS_WORK_IN_PROGRESS,
    \app\models\PropertyRecord::PROPERTY_STATUS_APPRAISAL_COMPLETE,
    \app\models\PropertyRecord::PROPERTY_STATUS_APPROVED_BY_CHARTERED_SURVEYOR,
    \app\models\PropertyRecord::PROPERTY_STATUS_PASSED_TO_SOLICITOR,
    \app\models\PropertyRecord::PROPERTY_STATUS_ALL_JOBS
];
$adjustPjaxSettings = <<<EOL
    $.pjax.defaults.timeout = 5000;
EOL;




$this->registerJs($adjustPjaxSettings,\yii\web\View::POS_READY);


$this->title = $propertyRecord->status;

?>
<style type="text/css">
    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-top: 4px;
    }
</style>

<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <?php $form = \yii\widgets\ActiveForm::begin(['id'=>'updateStatusForm']) ?>
        <?=
            Select2::widget([
                'model' => $propertyRecord,
                'attribute' => 'status',
                'data' => Yii::$app->params['statusCollection'],
                'options' => ['placeholder' => 'Update status'],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'pluginEvents'=>[
                    'change'=>'function(e){
                        jQuery("#updateStatusForm").submit()
                  }'
                ],
            ]);
        ?>
        <?php \yii\widgets\ActiveForm::end() ?>
    </div>
</div>


<br />
<br />

<?php
    echo TabsX::widget([
        'enableStickyTabs' => true,
        'items' => [
            [
                'label' => 'Basic Information',
                'content' => $this->render(
                    '_basic_information_panel',
                    [
                        'propertyRecord'=>$propertyRecord,
                        'statusCollection'=>$statusCollection,
                        'owner'=>$owner,
                        'propertyNote'=>$propertyNote,
                        'propertyOwnerDataProvider'=>$propertyOwnerDataProvider
                    ]
                ),
                [
                        'id'=>'basicInformationTab'
                ]
            ],
            [
                'label' => 'Documents',
                'content' => $this->render(
                    '_documents_panel',
                    [
                        'propertyDocumentDataProvider'=>$propertyDocumentDataProvider,
                        'propertyDocument'=>$propertyDocument,
                        'propertyRecord'=>$propertyRecord
                    ]
                ),
                [
                    'id'=>'documentsTab'
                ]
            ],
            [
                'label' => 'Pre appraisal images',
                'content' => $this->render(
                    '_pre_appraisal_images_panel',
                    [
                        'preappraisalImage'=>$preappraisalImage,
                        'propertyRecord'=>$propertyRecord,
                        'preappraisalImageDataProvider'=>$preappraisalImageDataProvider,
                    ]
                ),
                [
                    'id'=>'preAppraisalImagesTab'
                ]

            ],
            [
                'label' => 'Images',
                'content' => $this->render('_property_images_panel',[
                        'propertyRecord'=>$propertyRecord,
                    ]),
                [
                    'id'=>'imagesTab'
                ]

            ],
            [
                'label' => 'Notes',
                'content' => $this->render('_notes_panel',[
                    'propertyNote'=>$propertyNote,
                    'propertyNotesDataProvider'=>$propertyNotesDataProvider
                ]),
                [
                    'id'=>'notesTab'
                ]

            ],
            [
                'label' => 'Triage',
                'content' => $this->render('_triage',[
                    'propertyRecord'=>$propertyRecord,
                    'triageDocument'=>$triageDocument,
                    'triageDocumentDataProvider'=>$triageDocumentDataProvider
                ]),
                [
                    'id'=>'triageTab'
                ]

            ]
        ]
    ]);
?>
