<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Cavity */

$this->title = 'Create Cavity';
$this->params['breadcrumbs'][] = ['label' => 'Cavities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cavity-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
