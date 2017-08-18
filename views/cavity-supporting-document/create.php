<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CavitySupportingDocument */

$this->title = 'Create Cavity Supporting Document';
$this->params['breadcrumbs'][] = ['label' => 'Cavity Supporting Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cavity-supporting-document-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
