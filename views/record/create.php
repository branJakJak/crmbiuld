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


?>
<style type="text/css">
    #crafty_postcode_lookup_result_option1 {
        width: 100% !important;
        padding: 8px;
        margin: 10px auto;
    }
</style>

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
            <hr>
            <label>Address Search</label>
            <?= Html::input('text','searchPostalCode','',['id'=>'searchPostalCode','class'=>'form-control'])?>
            <div id="result-panel"></div>
            <hr>
            <?= $form->field($preCreatedRecord, 'postcode') ?>
            <?= $form->field($preCreatedRecord, 'address1') ?>
            <?= $form->field($preCreatedRecord, 'address2') ?>
            <?= $form->field($preCreatedRecord, 'address3') ?>
            <?= $form->field($preCreatedRecord, 'town') ?>
            <?= $form->field($preCreatedRecord, 'country')->textInput(['readonly'=>true]) ?>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>
        <?php
            PanelWidget::end()
        ?>
    </div>
</div>


<script type="text/javascript">
    var placeSearch, autocomplete;
    var componentForm = {
        'propertyrecord-address1': 'long_name',
        'propertyrecord-address2': 'long_name',
        'propertyrecord-address3': 'long_name',
        'propertyrecord-town': 'long_name',
        'propertyrecord-country': 'long_name',
        'propertyrecord-postcode':'short_name'
    };
    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('searchPostalCode')),
            {types: ['geocode']});
        autocomplete.addListener('place_changed', fillInAddress);
    }
    function fillInAddress() {
        var place = autocomplete.getPlace();
        console.log(place);
        for (var component in componentForm) {
            document.getElementById(component).value = '';
            document.getElementById(component).disabled = false;
        }

        document.getElementById('propertyrecord-postcode').value = place.address_components[0].short_name;
        window.searchAddress();
//        document.getElementById('propertyrecord-address1').value = place.address_components[1].long_name;
//        document.getElementById('propertyrecord-address2').value = place.address_components[2].long_name;
//        document.getElementById('propertyrecord-address3').value = place.address_components[3].long_name;
//        document.getElementById('propertyrecord-town').value = place.address_components[4].long_name;
//        document.getElementById('propertyrecord-country').value = place.address_components[5].long_name;
    }
    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }
</script>
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyBspZlwYVvLnDac07_LpMxtpXgEtA-p-dU&libraries=places&callback=initAutocomplete" async defer></script>
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
        cp_obj.set("elem_county"   , "propertyrecord-country"); // optional
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