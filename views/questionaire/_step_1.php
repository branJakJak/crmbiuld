<?php

use derekisbusy\panel\PanelWidget;
use kartik\date\DatePicker;
use kartik\tabs\TabsX;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Cavity */
/* @var $supportingDocument \app\models\CavitySupportingDocument */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs('jQuery(".sidebar-toggle").click()',\yii\web\View::POS_READY)
?>
<div class="cavity-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
            <div class="col-md-6 col-lg-6">
                <?php
                    echo PanelWidget::begin([
                        'title' => 'Personal Information',
                        'type' => 'default',
                        'widget' => false,
                    ])
                ?>
                    <?= $form->field($model, 'title')->dropDownList(['Mr'=>'Mr','Ms'=>'Ms','Mrs'=>'Mrs'])->label("Salutation") ?>
                    <?= $form->field($model, 'firstname')->textInput(['maxlength' => true])->label("Firstname") ?>
                    <?= $form->field($model, 'lastname')->textInput(['maxlength' => true])->label("Lastname") ?>
                    <?=
                        $form->field($model, 'birthday')->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => 'Enter birthday'],
                            'pluginOptions' => [
                                'format' => 'dd/mm/yyyy',
                                'autoclose'=>true
                            ]
                        ])
                    ?>
                    <?= $form->field($model, 'telephone_number')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'email_address')->textInput(['maxlength' => true]) ?>
                <?php
                    PanelWidget::end()
                ?>

            </div>

            <div class="col-md-6 col-lg-6">
                <?php
                    echo PanelWidget::begin([
                        'title' => 'Address Cavity was Installed and when they moved in to the property',
                        'type' => 'default',
                        'widget' => false,
                    ])
                ?>

                    <?= $form->field($model, 'address1_cavity_installation')->textInput(['maxlength' => true,'placeholder'=>'Address 1'])->label("") ?>

                    <?= $form->field($model, 'address2_cavity_installation')->textInput(['maxlength' => true,'placeholder'=>'Address 2'])->label("") ?>

                    <?= $form->field($model, 'address3_cavity_installation')->textInput(['maxlength' => true,'placeholder'=>'Address 3'])->label("") ?>

                    <?= $form->field($model, 'address_postcode_cavity_installation')->textInput(['maxlength' => true,'placeholder'=>'Postcalcode'])->label("") ?>

                    <?= $form->field($model, 'address_town_cavity_installation')->textInput(['maxlength' => true,'placeholder'=>'Town'])->label("") ?>

                    <?=
                        $form->field($model, 'when_property_moved')->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => 'Date when the property was moved'],
                            'pluginOptions' => [
                                'format' => 'dd/mm/yyyy',
                                'autoclose'=>true
                            ]
                        ])
                    ?>


                <?php
                    PanelWidget::end()
                ?>
                

            </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <?php
                echo PanelWidget::begin([
                    'title' => 'CWI Information',
                    'type' => 'default',
                    'widget' => false,
                ])
            ?>
            <?= 
                $form->field($model, 'CWI_installation_date')->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => 'dd/mm/yyyy'],
                            'pluginOptions' => [
                                'format' => 'dd/mm/yyyy',
                                'autoclose'=>true
                            ]
                ])

             ?>

            <?= $form->field($model, 'CWI_installer')->textInput(['maxlength' => true])->label('Which company installed the CWI') ?>

            <?= $form
                ->field($model, 'construction_type')
                ->dropDownList([
                        'Timber Framed'=>'Timber Framed',
                        'Steel Framed'=>'Steel Framed',
                        'Concrete'=>'Concrete'
                    ])
                ->label('What type of construction is the property?') ?>

            <?= 
            $form
            ->field($model, 'property_exposure')->textInput(['maxlength' => true])
            ->dropDownList([
                    ''=>'N/A',
                    'Yes'=>'Yes',
                    'No'=>'No'
                ])            
            ->label('Is the property particularly exposed to rain, wind or near a river/sea or in an area which doesn’t receive much sunlight') ?>

            <?= $form->field($model, 'CWI_payment')->textInput()->label('How much did they pay for the CWI / was it provided by their energy company as part of grant') ?>

            <?= 
                $form
                ->field($model, 'after_CWI_installation_comment')
                ->dropDownList([
                        ''=>'N/A',
                        'damp'=>'damp',
                        'condensation'=>'condensation',
                        'mould'=>'mould',
                        'odour'=>'odour',
                        'wallpaper falling off the walls'=>'wallpaper falling off the walls',
                        'damage to furniture or other personal property'=>'damage to furniture or other personal property',
                ])
                ->label('When (after the CWI installation) did they first notice problems (damp, condensation, mould, odour, wallpaper falling off the walls, damage to furniture or other personal property')
            ?>
            <?php
                PanelWidget::end()
            ?>
        </div>
        <div class="col-md-6 col-lg-6">
            <?php
                echo PanelWidget::begin([
                    'title' => '(Evident of Damp cases only)',
                    'type' => 'default',
                    'widget' => false,
                ])
            ?>
            <?= 
            $form
            ->field($model, 'suffered_issues_prior_to_CWI')->textInput(['maxlength' => true])
            ->dropDownList([
                    ''=>'N/A',
                    'Yes'=>'Yes',
                    'No'=>'No'
                ])            

            ->label('Had they suffered any such issues prior to the CWI installation') ?>

            <?= $form->field($model, 'work_to_rectify_CWI')->textInput(['maxlength' => true])->label('Have they had any work to remove or rectify the CWI – if so, when was this done, by who and at what cost') ?>

            <?= 
            $form->field($model, 'incured_financial_expenses')
            ->textInput(['maxlength' => true])
            ->dropDownList([
                    ''=>'N/A',
                    'Yes'=>'Yes',
                    'No'=>'No'
                ])            
            ->label('Have they incurred any other financial expense as a result') ?>

            <?= $form
            ->field($model, 'document_copy')
            ->dropDownList([
                    ''=>'N/A',
                    'Yes'=>'Yes',
                    'No'=>'No'
                ])            
            ->label('Do they have copies of any documents : original invoice, surveyors report, photos of damage') ?>

            <?= $form
            ->field($model, 'reported_issue_to_house_insurer')
            ->dropDownList([
                    ''=>'N/A',
                    'Yes'=>'Yes',
                    'No'=>'No'
                ])            
            ->label('Have they reported the issues to their house insurers, to the original installers, to the Cavity Insulation Guarantee Agency (CIGA) or anyone else') ?>

            <?= $form
            ->field($model, 'advice_about_suitability')
            ->dropDownList([
                    ''=>'N/A',
                    'Yes'=>'Yes',
                    'No'=>'No'
                ])            

            ->label('Were they provided with any advice about the suitability of CWI prior to installation') ?>

            <?php
                PanelWidget::end()
            ?>
        </div>        
    </div>

    <?php ActiveForm::end(); ?>

</div>

