<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 7/10/2017
 * Time: 6:07 PM
 */
use derekisbusy\panel\PanelWidget;
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this \yii\web\View
 */

$statusCollection = [
    \app\models\PropertyRecord::PROPERTY_STATUS_NOT_SUBMITTED=>\app\models\PropertyRecord::PROPERTY_STATUS_NOT_SUBMITTED,
    \app\models\PropertyRecord::PROPERTY_STATUS_PENDING_SUPERVISOR_APPROVAL=>\app\models\PropertyRecord::PROPERTY_STATUS_PENDING_SUPERVISOR_APPROVAL,
    \app\models\PropertyRecord::PROPERTY_STATUS_PENDING_ADMIN_APPROVAL=>\app\models\PropertyRecord::PROPERTY_STATUS_PENDING_ADMIN_APPROVAL,
    \app\models\PropertyRecord::PROPERTY_STATUS_REJECTED=>\app\models\PropertyRecord::PROPERTY_STATUS_REJECTED,
    \app\models\PropertyRecord::PROPERTY_STATUS_APPROVED=>\app\models\PropertyRecord::PROPERTY_STATUS_APPROVED,
    \app\models\PropertyRecord::PROPERTY_STATUS_WORK_IN_PROGRESS=>\app\models\PropertyRecord::PROPERTY_STATUS_WORK_IN_PROGRESS,
    \app\models\PropertyRecord::PROPERTY_STATUS_APPRAISAL_COMPLETE=>\app\models\PropertyRecord::PROPERTY_STATUS_APPRAISAL_COMPLETE,
    \app\models\PropertyRecord::PROPERTY_STATUS_APPROVED_BY_CHARTERED_SURVEYOR=>\app\models\PropertyRecord::PROPERTY_STATUS_APPROVED_BY_CHARTERED_SURVEYOR,
    \app\models\PropertyRecord::PROPERTY_STATUS_PASSED_TO_SOLICITOR=>\app\models\PropertyRecord::PROPERTY_STATUS_PASSED_TO_SOLICITOR,
    \app\models\PropertyRecord::PROPERTY_STATUS_ALL_JOBS=>\app\models\PropertyRecord::PROPERTY_STATUS_ALL_JOBS,
];
$insulationType = [
    'CWI' => 'CWI',
    'EWI' => 'EWI'
];

$this->registerJsFile('@web/js/crafty_postcode.class.js');
$cccCode = <<<EOL
    initAddressSearch();
EOL;
$this->registerJs($cccCode , \yii\web\View::POS_READY);

$postalSearchCode = <<<EOL
jQuery("#propertyrecord-postcode").blur(function(){
    window.searchAddress()
});
EOL;
$this->registerJs($postalSearchCode , \yii\web\View::POS_READY);



?>
<script type="text/javascript">
    var cp_obj;
    function initAddressSearch(){
        var _cp_token_fe = "90197-6843a-b808b-014ff";
        var _cp_busy_img_url = '/img/crafty_postcode_busy.gif';
        var _cp_err_msg1 = 'This postcode could not be found, please try again or enter your address manually';
        var _cp_err_msg2 = 'This postcode is not valid, please try again or enter your address manually';
        var _cp_err_msg3 = 'Unable to connect to address lookup server, please enter your address manually';
        var _cp_err_msg4 = 'An unexpected error occurred, please enter your address manually';
        cp_obj = CraftyPostcodeCreate();
        // config
        cp_obj.set("access_token", _cp_token_fe);
        cp_obj.set("res_autoselect", "0");
        cp_obj.set("result_elem_id", 'result-panel');
        cp_obj.set("form", "");
        cp_obj.set("elem_company"  , "");
        cp_obj.set("elem_house_num", "");
        cp_obj.set("elem_street1"  , 'propertyrecord-address1');
        cp_obj.set("elem_street2"  , 'propertyrecord-address2');
        cp_obj.set("elem_street3"  , 'propertyrecord-address3]');
        cp_obj.set("elem_town"     , 'propertyrecord-town');
        cp_obj.set("elem_county"   , ""); // optional
        cp_obj.set("elem_postcode" , 'propertyrecord-postcode');
        cp_obj.set("single_res_autoselect" , 1);
        cp_obj.set("busy_img_url" , _cp_busy_img_url);
        cp_obj.set("err_msg1", _cp_err_msg1);
        cp_obj.set("err_msg2", _cp_err_msg2);
        cp_obj.set("err_msg3", _cp_err_msg3);
        cp_obj.set("err_msg4", _cp_err_msg4);
    }
    function searchAddress(){
        cp_obj.doLookup();
    }

</script>

<div class="row">
    <div class="col-lg-6">
        <?php
            echo PanelWidget::begin([
                'title'=>'Property Details',
                'type'=>'info',
                'widget'=>false,
            ])
        ?>
        <?php $form = ActiveForm::begin(); ?>
            <?= $form
                ->field($preCreatedRecord, 'insulation_type')
                ->dropDownList($insulationType)
            ?>
            <div id="result-panel"></div>
            <?= $form->field($preCreatedRecord, 'postcode') ?>
            <?= $form->field($preCreatedRecord, 'address1') ?>
            <?= $form->field($preCreatedRecord, 'address2') ?>
            <?= $form->field($preCreatedRecord, 'address3') ?>
            <?= $form->field($preCreatedRecord, 'town') ?>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>
        <?php
            PanelWidget::end()
        ?>
    </div>
</div>
