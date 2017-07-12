<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 7/10/2017
 * Time: 6:10 PM
 */
use kartik\tabs\TabsX;


/* @var $this yii\web\View */
/* @var $propertyRecord \app\models\PropertyRecord */
/* @var $owner \app\models\Owner */
/* @var $propertyNote \app\models\PropertyNotes */


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


?>
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
            ],
            [
                'label' => 'Images',
//                'content' => $this->render('_property_images_panel'),
                'content' => '',
            ],
            [
                'label' => 'Notes',
                'content' => $this->render('_notes_panel',[
                    'propertyNote'=>$propertyNote,
                    'propertyNotesDataProvider'=>$propertyNotesDataProvider
                ]),
            ],
        ]
    ]);
?>
