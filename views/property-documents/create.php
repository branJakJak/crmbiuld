<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PropertyDocuments */

$this->title = 'Create Property Documents';
$this->params['breadcrumbs'][] = ['label' => 'Property Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-documents-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
