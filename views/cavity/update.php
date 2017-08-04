<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cavity */

$this->title = 'Cavity Questionaire';
$this->params['breadcrumbs'][] = ['label' => 'Cavities', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<?= Html::a("View Information",\yii\helpers\Url::to(['/cavity/view','id'=>$model->id]),['class'=>'btn btn-lg btn-info'])  ?>
<br>
<br>
<div class="cavity-update">
    <?= $this->render('_form', [
        'model' => $model,
        'supportingDocument' => $supportingDocument,
    ]) ?>

</div>
