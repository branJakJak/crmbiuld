<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PropertyImages */

$this->title = 'Create Property Images';
$this->params['breadcrumbs'][] = ['label' => 'Property Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-images-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
