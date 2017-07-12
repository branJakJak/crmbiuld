<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PropertyNotes */

$this->title = 'Create Property Notes';
$this->params['breadcrumbs'][] = ['label' => 'Property Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-notes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
