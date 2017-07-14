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


?>

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
            <hr>
            <?= $form->field($preCreatedRecord, 'postcode') ?>
            <?= $form->field($preCreatedRecord, 'address1') ?>
            <?= $form->field($preCreatedRecord, 'address2') ?>
            <?= $form->field($preCreatedRecord, 'address3') ?>
            <?= $form->field($preCreatedRecord, 'town') ?>
            <?= $form->field($preCreatedRecord, 'country') ?>
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
        document.getElementById('propertyrecord-address1').value = place.address_components[1].long_name;
        document.getElementById('propertyrecord-address2').value = place.address_components[2].long_name;
        document.getElementById('propertyrecord-address3').value = place.address_components[3].long_name;
        document.getElementById('propertyrecord-town').value = place.address_components[4].long_name;
        document.getElementById('propertyrecord-country').value = place.address_components[5].long_name;
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