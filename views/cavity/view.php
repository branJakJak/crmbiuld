<?php

use derekisbusy\panel\PanelWidget;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Cavity */

$this->title = "View Information";
$this->params['breadcrumbs'][] = ['label' => 'Cavities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$tooltip = <<<EOL
$('[data-toggle="popover"]').popover();
$('[data-toggle="tooltip"]').tooltip();
EOL;

$this->registerJs($tooltip);

?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>
            <?= Html::a('Update record', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete record', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="cavity-view">
            <?php
                echo PanelWidget::begin([
                    'title' => 'Personal Information',
                    'type' => 'default',
                    'widget' => false,
                ])
            ?>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'title',
                    'firstname',
                    'lastname',
                    'birthday',
                    'telephone_number',
                    'email_address:email',
                    'address1_cavity_installation',
                    'address2_cavity_installation',
                    'address3_cavity_installation',
                    'address_postcode_cavity_installation',
                    'address_town_cavity_installation',
                    'address_country_cavity_installation',
                    'CWI_installation_date',
                    'CWI_installer',
                    'construction_type',
                    'property_exposure',
                    'CWI_payment',
                    'after_CWI_installation_comment',
                    'suffered_issues_prior_to_CWI',
                    'work_to_rectify_CWI',
                    'incured_financial_expenses',
                    'document_copy',
                    'reported_issue_to_house_insurer',
                    'advice_about_suitability',
                    'date_time_callback',
                    'date_created',
                    'date_updated',
                ],
            ]) ?>
            <?php
                PanelWidget::end()
            ?>
        </div>

    </div>
    <div class="col-lg-6">
        <?php
        echo PanelWidget::begin([
            'title' => 'Supporting Documents',
            'type' => 'default',
            'widget' => false,
        ])
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
//                ['class' => 'yii\grid\SerialColumn'],
//                'id',
//                'document_name',
                [
                    'label'=>'Document',
                    'value'=>function($model){
                        $mes = 'Not Specified';
                        if($model->type === \app\models\CavitySupportingDocument::FILE_TYPE_GUARANTOR_CERTIFICATE){
                            $mes = 'Guarantee cert or communication confirming which company installed the cavity showing date when installed.';
                        } else if($model->type === \app\models\CavitySupportingDocument::FILE_TYPE_PHOTO){
                            $mes = 'Photo ID ie driving licence or passport';
                        } else if($model->type === \app\models\CavitySupportingDocument::FILE_TYPE_PROOF_OF_ADDRESS){
                            $mes = 'Proof of address';
                        }
                        $htmlOptions = [
                            'data-toggle'=>"tooltip",
                            'title'=>$mes,
                        ];
                        return Html::a($model->document_name, \yii\helpers\Url::to(['cavity-supporting-document/download', 'id' => $model->id]) , $htmlOptions);
                    },
                    'format'=>'raw',
                    'attribute'=>'document_name'
                ],

//                'type',
//                'document_name',
//                'date_created',
//                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

        <?php
        PanelWidget::end()
        ?>

    </div>
</div>
