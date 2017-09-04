<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 7/10/2017
 * Time: 8:56 PM
 */
use derekisbusy\panel\PanelWidget;
use kartik\tabs\TabsX;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $propertyRecord \app\models\PropertyRecord */
/* @var $statusCollection array */
/* @var $propertyNote \app\models\PropertyNotes */

$propertyType = [
    "" => "---------------- Select ----------------",
    "Mid Terrace" => "Mid Terrace",
    "End Terrace" => "End Terrace",
    "Semi Detached House" => "Semi Detached House",
    "Detached House" => "Detached House",
    "Semi Detached Bungalow" => "Semi Detached Bungalow",
    "Detached Bungalow" => "Detached Bungalow",
    "Semi Detached Dormer Bungalow" => "Semi Detached Dormer Bungalow",
    "Detached Dormer Bungalow" => "Detached Dormer Bungalow",
    "Flat - HMO" => "Flat - HMO",
    "Flat - Purpose Built" => "Flat - Purpose Built"
];
?>
<div class="row">

    <div class="col-lg-4">
        <?php
        echo PanelWidget::begin([
            'title' => 'Property Details',
            'type' => 'default',
            'widget' => false,
        ])
        ?>
        <?php $form = \yii\widgets\ActiveForm::begin() ?>
        <label for="">Date created</label>
        <h4>
            <?=
            Yii::$app->formatter->asDatetime(new DateTime(($propertyRecord->date_created)));
            ?>
        </h4>
        <br>
        <?= $form->field($propertyRecord, 'insulation_type')->dropDownList(['CWI' => 'CWI', 'EWI' => 'EWI']) ?>
        <?= $form->field($propertyRecord, 'postcode') ?>
        <?= $form->field($propertyRecord, 'address1') ?>
        <?= $form->field($propertyRecord, 'town') ?>
        <?= $form->field($propertyRecord, 'property_type')->dropDownList($propertyType) ?>
        <?= $form->field($propertyRecord, 'number_of_bedrooms') ?>
        <?= $form->field($propertyRecord, 'approximate_build') ?>
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        <?php \yii\widgets\ActiveForm::end() ?>

        <?php
        PanelWidget::end()
        ?>
    </div>

    <div class="col-lg-4">
        <?php
        echo PanelWidget::begin([
            'title' => 'Property Owner Details',
            'type' => 'default',
            'widget' => false,
        ])
        ?>


        <?php $this->beginBlock('list_of_owner') ?>
        <!--get all owner and list-->
        <?=
        \yii\grid\GridView::widget([
            'dataProvider' => $propertyOwnerDataProvider,
            'columns' => [
                [
                    'label' => 'Name',
                    'value' => function ($currentModel) {
                        /* @var \app\models\PropertyOwner $currentModel */
                        /* @var \app\models\Owner $currentOwner */
                        $currentOwner = $currentModel->getOwner()->one();
                        $ownerFullName = 'n/a';
                        if ($currentOwner) {
                            $ownerFullName = sprintf("%s %s %s ", $currentOwner->title, $currentOwner->firstname, $currentOwner->lastname);
                            $ownerFullName = Html::a(Html::encode($ownerFullName), ['/owner/view', 'id' => $currentOwner->id]);
                        }
                        return $ownerFullName;
                    },
                    'attribute' => 'id',
                    'format' => 'html'
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('yii', 'Delete'),
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ]
                            ]);
                        }
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'delete') {
                            $url = '/owner/delete/' . $model->owner_id;
                            return $url;
                        }
                    }
                ],
            ],
        ]);
        ?>
        <?php $this->endBlock() ?>

        <?php $this->beginBlock('create_new_owner_form') ?>

        <?= $this->render('/owner/_form', ['model' => $owner]) ?>

        <?php $this->endBlock() ?>
        <!--tab for property owner-->
        <?php
        echo TabsX::widget([
            'items' => [
                [
                    'label' => 'Owners',
                    'content' => $this->blocks['list_of_owner'],
                    [
                            'id'=>'ownersTab'
                    ]
                ],
                [
                    'label' => 'Create new owner',
                    'content' => $this->blocks['create_new_owner_form'],
                ]
            ]
        ]);
        ?>



        <?php
        PanelWidget::end()
        ?>
    </div>


    <div class="col-lg-4">
        <?php
        echo PanelWidget::begin([
            'title' => 'Latest Note',
            'type' => 'default',
            'widget' => false,
        ])
        ?>
        <?php
        /* @var $lastNote \app\models\PropertyNotes */
        /* @var $tempNoteCreator \dektrium\user\models\User */
        /* @var $tempNoteCreatorProfile \dektrium\user\models\Profile */
        $lastNote = $propertyRecord->getPropertyNotes()->orderBy(['date_created' => SORT_DESC])->one();
        $noteCreator = 'n/a';
        $noteDatePublished = '';
        $noteContent = '';
        if ($lastNote) {
            $tempProfile = '';
            $tempNoteCreatorProfile = '';
            $creatorObj = $lastNote->getCreator();
            if($creatorObj){
                $creatorObj = $lastNote->getCreator();
                $tempNoteCreator = $creatorObj->one();
                if($tempNoteCreator){
                    $tempProfile = $tempNoteCreator->getProfile();
                    if ($tempProfile) {
                        $tempNoteCreatorProfile = $tempProfile->one();
                        $noteCreator = $tempNoteCreatorProfile->name;
                        $noteCreator = $tempNoteCreatorProfile->name;
                    }
                }
                $noteContent = $lastNote->content;
            }
            $noteDatePublished = $lastNote->date_created;
        }
        ?>
        <?php if ($lastNote): ?>
            <?= $noteCreator ?> -<?= Yii::$app->formatter->asDate(new DateTime($noteDatePublished)) ?> <br>
            <?= Html::encode($noteContent) ?>
        <?php endif ?>
        <?php if (!$lastNote): ?>
            <strong>No notes created yet</strong>
        <?php endif ?>

        <?php
        PanelWidget::end()
        ?>

        <?php
        echo PanelWidget::begin([
            'title' => 'Installation / Insulation Details',
            'type' => 'default',
            'widget' => false,
        ])
        ?>
        <?php $form = \yii\widgets\ActiveForm::begin() ?>
        <label for="">Date of CWI</label>
        <?=
        \kartik\date\DatePicker::widget([
            'model' => $propertyRecord,
            'attribute' => 'date_of_cwi',
        ])
        ?>
        <br>
        <?= $form->field($propertyRecord, 'installer') ?>
        <?= $form->field($propertyRecord, 'product_installed') ?>
        <?= $form->field($propertyRecord, 'system_designer') ?>
        <?= $form->field($propertyRecord, 'guarantee_provider') ?>
        <?= $form->field($propertyRecord, 'guarantee_number') ?>
        <label for="">Date Guarantee Issued</label>
        <?=

        \kartik\date\DatePicker::widget([
            'model' => $propertyRecord,
            'attribute' => 'date_guarantee_issued',
        ])
        ?>
        <br>
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        <?php \yii\widgets\ActiveForm::end() ?>
        <?php
        PanelWidget::end()
        ?>


    </div>

</div>
