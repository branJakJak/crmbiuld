<?php

use kartik\typeahead\Typeahead;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use derekisbusy\panel\PanelWidget;
use kartik\export\ExportMenu;
use kartik\widgets\Select2;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model app\models\PropertyRecord */
/* @var $form ActiveForm */
/* @var $insulationCollection array */

$availableUsersRes = \dektrium\user\models\Profile::find()->select("name")->asArray()->all();
$availableUser = [];
foreach ($availableUsersRes as $currentAvailableUser) {
    $availableUser[] = $currentAvailableUser['name'];
}


$jsScript = <<<EOL
        $("#toggleFilterTab").click(function(event) {
            $("#tab-filter-toggle").toggle('slow');
        });
EOL;
$this->registerJs($jsScript, View::POS_READY, 'toggle-script');


$gridColumns = [
    [
        'label' => 'ID',
        'value' => function ($currentModel) {
            /* @var $currentModel \app\models\PropertyRecord */
            return Html::a($currentModel->id, ['/record/update', 'id' => $currentModel->id]);
        },
        'attribute' => 'id',
        'format' => 'html'
    ],
    'status',
    'date_created:date',
    [
        'label' => 'Agent ID',
        'value' => function ($currentModel) {
            /* @var $currentModel \app\models\PropertyRecord */
            $createdByName = 'n/a';
            $modelFound = \dektrium\user\models\User::findOne(['id' => $currentModel->created_by]);
            if ($modelFound) {
                $profileObj = $modelFound->getProfile();
                $profileObj = $profileObj->one();
                if ($profileObj) {
                    $createdByName = $profileObj->name;
                }else{
                    $createdByName = $modelFound->username;
                }
            }
            return $createdByName;
        },
        'attribute' => 'created_by'
    ],
//    'appraisal_completed:date',
//    'address1',
    'postcode',
//    'insulation_type',
    [
        'label' => 'Latest Note',
        'value' => function ($currentModel) {
            /* @var $currentModel \app\models\PropertyRecord */
            $lastNote = '';
            $propertyNotes = $currentModel->propertyNotes;
            if (isset($propertyNotes) && !empty($propertyNotes)) {
                $lastNoteObj = $propertyNotes[count($propertyNotes) - 1];
                $lastNote = $lastNoteObj->content;
                if (strlen($lastNote) >= 250) {
                    $lastNote = substr($lastNote, 0, 250).'...'.Html::a('Read more', "/record/update/{$currentModel->id}#w28-tab4" , []);
                }
            }
            return $lastNote;
        },
        'format'=>'raw'
    ],
    
//    [
//        'label' => 'Clients',
//        'value' => function ($currentModel) {
//            /* @var $currentModel \app\models\PropertyRecord */
//            /* @var $currentPropertyOwner \app\models\PropertyOwner */
//            $propertyOwnersCollection = [];
//            $propertyOwners = $currentModel->getPropertyOwners()->all();
//            foreach ($propertyOwners as $currentPropertyOwner) {
//                $currentOwner = $currentPropertyOwner->getOwner()->one();
//                if ($currentOwner) {
//                    $ownerFullName = '';
//                    if (!empty($currentOwner->title)) {
//                        $ownerFullName = sprintf("%s %s %s", $currentOwner->title, $currentOwner->firstname, $currentOwner->lastname);
//                    }
//                    $tempContainer = Html::a($ownerFullName, ['/owner/view', 'id' => $currentOwner->id]);
//                    $propertyOwnersCollection[] = $tempContainer;
//                }
//            }
//            return implode(",<br>", $propertyOwnersCollection);
//        },
//        'format' => 'raw'
//    ],
];
if(Yii::$app->user->can('admin') || Yii::$app->user->can('Admin')){
    $gridColumns[] = [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{delete}',//{view}{update}
        'buttons' => [
            'delete' => function ($url, $model) {
                return Html::a(
                    '<span class="glyphicon glyphicon-trash"></span>',
                    Url::to(['record/delete', 'id' => $model->id]),
                    [
                        'class' => 'btn',
                        'style' => 'color: red !important',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]);

            }

        ],
    ];
}




?>
<style type="text/css">
    #select2-filterpropertyrecordform-status-container {
        margin-top: 0px;
    }

    #w8 > div {
        text-align: right;
    }
</style>
<div class="site-index">

    <?php if (!Yii::$app->user->can('Manager') && !Yii::$app->user->can('Agent')): ?>
    <div class="row">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <?= Html::a("Create a New Record", \yii\helpers\Url::to("/record/create"), ['class' => 'btn btn-info btn-lg']) ?>
        </div>
    </div>
    <?php endif ?>

    <?php if (Yii::$app->user->can('Agent') || Yii::$app->user->can('Manager')): ?>
    <div class="row">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <?php echo $filterModel->status ?>
        </div>
    </div>
    <?php endif ?>

    
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <?php $form = ActiveForm::begin(['id' => 'status-filter-form']); ?>
            <input type="hidden" name="scenario" class="form-control" value="status-filter-form">
            <?=
            Select2::widget([
                'model' => $filterModel,
                'attribute' => 'status',
                'data' => Yii::$app->params['statusCollection'],
                'options' => ['placeholder' => 'Filter as you type ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'pluginEvents' => [
                    'change' => 'function(e){
                                jQuery("#status-filter-form").submit()
                            }'
                ],
            ]);
            ?>
            <?php ActiveForm::end(); ?>
        </div>
        <br>
    </div>
    

    <br>
    <?php
    echo PanelWidget::begin([
        'title' => 'Browse Jobs',
        'type' => 'info',
        'widget' => false,
    ])
    ?>
    <?php $this->beginBlock('quick_filter_block') ?>

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($filterModel, 'filterQuery')->textInput(['placeholder' => 'Search'])->label('') ?>
    <input type="hidden" name="scenario" class="form-control" value="quick-filter-form">
    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <?php $this->endBlock() ?>

    <?php $this->beginBlock('fine_filter_block') ?>
    <?php $form = ActiveForm::begin(); ?>
    <input type="hidden" name="scenario" class="form-control" value="fine-filter-form">
    <div>

        <div class="col-lg-4">
            <label for="">Date created</label>
            <?=
            \kartik\date\DatePicker::widget([
                'model' => $filterModel,
                'attribute' => 'date_created',
            ])
            ?>
        </div>
        <div class="col-lg-4">
            <label for="">Appraisal Completed</label>
            <?=
            \kartik\date\DatePicker::widget([
                'model' => $filterModel,
                'attribute' => 'appraisal_completed',
            ])
            ?>
        </div>

        <?= $form->field($filterModel, 'address1', ['template' => "<div class=\"col-lg-4\">{label}{input}{error}{hint}</div>"]) ?>
        <?= $form->field($filterModel, 'postcode', ['template' => "<div class=\"col-lg-4\">{label}{input}{error}{hint}</div>"]) ?>

        <?= $form
            ->field($filterModel, 'insulation_type', ['template' => "<div class=\"col-lg-4\">{label}{input}{error}{hint}</div>"])
            ->widget(Typeahead::classname(), [
                'dataset' => [
                    [
                        'local' => \yii\helpers\ArrayHelper::merge($insulationCollection, ['']),
                    ]
                ],
                'pluginOptions' => ['highlight' => true],
                'options' => ['placeholder' => 'Filter as you type ...'],
            ]);
        ?>


        <?= $form
            ->field($filterModel, 'created_by_username', ['template' => "<div class=\"col-lg-4\">{label}{input}{error}{hint}</div>"])
            ->widget(Typeahead::classname(), [
                'dataset' => [
                    [
                        'local' => \yii\helpers\ArrayHelper::merge($availableUser, ['']),
                    ]
                ],
                'pluginOptions' => ['highlight' => true],
                'options' => ['placeholder' => 'Filter as you type ...'],
            ]);

        ?>


        <?= $form->field($filterModel, 'latest_note', ['template' => "<div class=\"col-lg-4\">{label}{input}{error}{hint}</div>"]) ?>

        <?= $form
            ->field($filterModel, 'status', ['template' => "<div class=\"col-lg-4\">{label}{input}{error}{hint}</div>"])
            ->widget(Typeahead::classname(), [
                'dataset' => [
                    [
                        'local' => Yii::$app->params['statusCollection'],
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
    <?php $this->endBlock() ?>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 hidden">
            <div id="toggleFilterTab" style="margin: 20px;margin-left:0px;" class='btn btn-link'>
                <i class="fa fa-caret-square-o-down" aria-hidden="true"></i>
                Other Filter
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id='tab-filter-toggle'>
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
            <strong style="font-size: 28px;">
                Export Data
            </strong>
            <br>
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
                'columns' => $gridColumns,
            ]);

            ?>
        </div>
    </div>


    <?php
        PanelWidget::end()
    ?>

</div>
