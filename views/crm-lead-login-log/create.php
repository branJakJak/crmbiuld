<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CrmLeadLoginLog */

$this->title = 'Create Crm Lead Login Log';
$this->params['breadcrumbs'][] = ['label' => 'Crm Lead Login Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crm-lead-login-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
