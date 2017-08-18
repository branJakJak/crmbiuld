<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Triage */

$this->title = 'Create Triage';
$this->params['breadcrumbs'][] = ['label' => 'Triages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="triage-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
