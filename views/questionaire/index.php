<?php
/* @var $this yii\web\View */
/* @var $supportingDocument \app\models\CavitySupportingDocument */
/* @var $model \app\models\Cavity */

$wizard_config = [
    'id' => 'stepwizard',
    'steps' => [
        1 => [
            'title' => 'Contact Information',
            'icon' => 'glyphicon glyphicon-user',
            'content' => $this->render("_step_1",['model'=>$model]),
        ],
        2 => [
            'title' => 'Internal Photos',
            'icon' => 'glyphicon glyphicon-picture',
            'content' => $this->render("_step_2",[
            		'cavity_model'=>$model,
            		'supportingDocument'=>$supportingDocument,
            	]),
        ],
        // 3 => [
        //     'title' => 'External Photos',
        //     'icon' => 'glyphicon glyphicon-picture',
        //     'content' => $this->render("_step_3",['model'=>$model]),
        // ],
        // 4 => [
        //     'title' => 'Additional Documents',
        //     'icon' => 'glyphicon glyphicon-file',
        //     'content' => $this->render("_step_4",['model'=>$model]),
        // ],

    ],
    'complete_content' => "Form submitted! Your data is submitted and will be reviewed. ", // Optional final screen
    'start_step' => 1, // Optional, start with a specific step
];
?>
<div class="row" style='padding-bottom: 100px'>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
		<h1>
			Supporting evidence capture for your Cavity Wall Claim
			<p class='text-left' style='margin-left: 131px;font-size: 20px'>
				<small>
					<br >
					Thank you for your time on the phone. 
					<br>
					To progress your claim we require some supporting evidence of the issues you are experiencing.
				</small>
			</p>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?= \drsdre\wizardwidget\WizardWidget::widget($wizard_config); ?>
	</div>
</div>