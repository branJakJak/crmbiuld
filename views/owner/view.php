<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Owner */

$this->title = sprintf("%s. %s %s",$model->title,$model->firstname,$model->lastname) ;
$this->params['breadcrumbs'][] = ['label' => 'Owners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="owner-view">

    <h3> Property owner : <?= Html::encode($this->title) ?></h3>
    <p class="">
        <?php if(Yii::$app->user->can('admin')): ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'firstname',
            'lastname',
            'company_name',
            'email_address:email',
            'mobile_number',
            'phone_number',
            'date_of_birth:date',
            'address1',
            'address2',
            'address3',
            'postalcode',
            'town',
            'country',
            'date_created:date',
//            'date_updated:date',
        ],
    ]) ?>

</div>
