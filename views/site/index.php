<?php

use kartik\typeahead\Typeahead;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use derekisbusy\panel\PanelWidget;
use kartik\export\ExportMenu;


/* @var $this yii\web\View */
/* @var $model app\models\PropertyRecord */
/* @var $form ActiveForm */
/* @var $insulationCollection array */

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
$availableUsersRes = \dektrium\user\models\Profile::find()->select("name")->asArray()->all();
$availableUser = [];
foreach ($availableUsersRes as $currentAvailableUser) {
    $availableUser[] = $currentAvailableUser['name'];
}




?>
<div class="site-index">
    <?php
        echo PanelWidget::begin([
            'title'=>'Browse Jobs',
            'type'=>'info',
            'widget'=>false,
        ])
    ?>
    <?php $this->beginBlock('quick_filter_block')?>
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($filterModel, 'filterQuery')->textInput(['placeholder'=>'Search'])->label('') ?>
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    <?php $this->endBlock()?>

    <?php $this->beginBlock('fine_filter_block')?>
        <?php $form = ActiveForm::begin(); ?>

        <div >

            <div class="col-lg-4">
                <label for="">Date created</label>
                <?=
                \kartik\date\DatePicker::widget([
                    'model'=>$filterModel,
                    'attribute'=>'date_created',
                ])
                ?>
            </div>
            <div class="col-lg-4">
                <label for="">Appraisal Completed</label>
                <?=
                \kartik\date\DatePicker::widget([
                    'model'=>$filterModel,
                    'attribute'=>'appraisal_completed',
                ])
                ?>
            </div>

            <?= $form->field($filterModel, 'address1',['template' => "<div class=\"col-lg-4\">{label}{input}{error}{hint}</div>"]) ?>
            <?= $form->field($filterModel, 'zipcode',['template' => "<div class=\"col-lg-4\">{label}{input}{error}{hint}</div>"]) ?>

            <?= $form
                ->field($filterModel, 'insulation_type',['template' => "<div class=\"col-lg-4\">{label}{input}{error}{hint}</div>"])
                ->widget(Typeahead::classname(), [
                    'dataset' => [
                        [
                            'local' => \yii\helpers\ArrayHelper::merge($insulationCollection,['']),
                        ]
                    ],
                    'pluginOptions' => ['highlight' => true],
                    'options' => ['placeholder' => 'Filter as you type ...'],
                ]);
            ?>


            <?= $form
                ->field($filterModel, 'created_by_username',['template' => "<div class=\"col-lg-4\">{label}{input}{error}{hint}</div>"])
                ->widget(Typeahead::classname(), [
                    'dataset' => [
                        [
                            'local' => \yii\helpers\ArrayHelper::merge($availableUser,['']),
                        ]
                    ],
                    'pluginOptions' => ['highlight' => true],
                    'options' => ['placeholder' => 'Filter as you type ...'],
                ]);

            ?>


            <?= $form->field($filterModel, 'latest_note',['template' => "<div class=\"col-lg-4\">{label}{input}{error}{hint}</div>"]) ?>

            <?= $form
                ->field($filterModel, 'status',['template' => "<div class=\"col-lg-4\">{label}{input}{error}{hint}</div>"])
                ->widget(Typeahead::classname(), [
                    'dataset' => [
                        [
                            'local' => $statusCollection,
                        ]
                    ],
                    'pluginOptions' => ['highlight' => true],
                    'options' => ['placeholder' => 'Filter as you type ...'],
                ]);
            ?>

        </div>

        <div class="col-lg-12">
            <br>
            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    <?php $this->endBlock()?>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php
            echo Tabs::widget([
                'items' => [
                    [
                        'label' => 'Quick Filter',
                        'content' => $this->blocks['quick_filter_block'],
                    ],
                    [
                        'label' => 'Fine Filter',
                        'content' => $this->blocks['fine_filter_block'],
                    ]
                ]
            ]);
            ?>
        </div>
    </div>
    <hr>
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php
            echo ExportMenu::widget([
                'dataProvider' => $dataProvider
            ]);
            ?>
        </div>


    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php
            echo \yii\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'label' => ' ',
                        'value' => function($currentModel){
                            /* @var $currentModel \app\models\PropertyRecord*/
                            return Html::a('View',['/record/update','id'=>$currentModel->id ]);
                        },
                        'attribute'=>'id',
                        'format'=>'html'
                    ],
                    'date_created:datetime',
                    'appraisal_completed:date',
                    'address1',
                    'zipcode',
                    'insulation_type',
                    [
                        'label' => 'Created by',
                        'value' => function($currentModel){
                            /* @var $currentModel \app\models\PropertyRecord*/
                            $createdByName = 'n/a';
                            $modelFound = \dektrium\user\models\User::findOne(['id' => $currentModel->created_by]);
                            if($modelFound){
                                $profileObj = $modelFound->getProfile();
                                $profileObj = $profileObj->one();
                                $createdByName = $profileObj->name;
                            }
                            return $createdByName;
                        },
                        'attribute'=>'created_by'
                    ],
                    [
                        'label' => 'Latest Note',
                        'value' => function($currentModel){
                            /* @var $currentModel \app\models\PropertyRecord*/
                            $lastNote = '';
                            $propertyNotes = $currentModel->propertyNotes;
                            if(isset($propertyNotes) && !empty($propertyNotes) ){
                                $lastNoteObj = $propertyNotes[count($propertyNotes)-1];
                                $lastNote = $lastNoteObj->content;
                            }
                            return $lastNote;
                        },
                    ],
                    'status'
                ],
            ]);

            ?>
        </div>
    </div>





    <?php
        PanelWidget::end()
    ?>

</div>
