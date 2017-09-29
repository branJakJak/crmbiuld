<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use dektrium\user\widgets\Connect;
use dektrium\user\models\LoginForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module $module
 */

$this->title = Yii::t('user', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;



$openLoginFormBtnScript = <<<EOL
    jQuery("#openLoginFormBtn").click(function(event) {
        event.preventDefault();
        jQuery("#loginAdminForm").toggleClass('hidden');

    });

EOL;
$this->registerJs($openLoginFormBtnScript);

?>
<style type="text/css">
    .login-page, .register-page {
        background: white;
    }
</style>


<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <img src="/img/White Collar Logo.jpg" class="img-responsive" alt="Image" style="
    margin: 0px auto;
">
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        
    </div>
    <div class="col-lg-4">
        <br>
        <?= Html::a('<i class="fa  fa-sticky-note-o"></i> Lead Questionaire Form', '//crmlead.whitecollarclaim.co.uk/', ['class' => 'btn btn-lg btn-success btn-block']); ?>
        <br>
        <?= Html::a('<i class="fa fa-user"></i> CRM Panel ', '#', ['class' => 'btn btn-lg btn-info btn-block','id'=>'openLoginFormBtn']); ?>
        
    </div>
</div>
<br >
<br >
<div class="row hidden" id="loginAdminForm">
    <div class="col-lg-4">
    </div>

    <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'enableAjaxValidation' => true,
                        'enableClientValidation' => false,
                        'validateOnBlur' => false,
                        'validateOnType' => false,
                        'validateOnChange' => false,
                    ]) ?>

                    <?php if ($module->debug): ?>
                        <?= $form->field($model, 'login', [
                            'inputOptions' => [
                                'autofocus' => 'autofocus',
                                'class' => 'form-control',
                                'tabindex' => '1']])->dropDownList(LoginForm::loginList());
                        ?>

                    <?php else: ?>

                        <?= $form->field($model, 'login',
                            ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]
                        );
                        ?>

                    <?php endif ?>

                    <?php if ($module->debug): ?>
                        <div class="alert alert-warning">
                            <?= Yii::t('user', 'Password is not necessary because the module is in DEBUG mode.'); ?>
                        </div>
                    <?php else: ?>
                        <?= $form->field(
                            $model,
                            'password',
                            ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']])
                            ->passwordInput()
                            ->label(
                                Yii::t('user', 'Password')
                                . ($module->enablePasswordRecovery ?
                                    ' (' . Html::a(
                                        Yii::t('user', 'Forgot password?'),
                                        ['/user/recovery/request'],
                                        ['tabindex' => '5']
                                    )
                                    . ')' : '')
                            ) ?>
                    <?php endif ?>

                    <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '3']) ?>

                    <?= Html::submitButton(
                        Yii::t('user', 'Sign in'),
                        ['class' => 'btn btn-primary btn-block', 'tabindex' => '4']
                    ) ?>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <?php if ($module->enableConfirmation): ?>
                <p class="text-center">
                    <?= Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/registration/resend']) ?>
                </p>
            <?php endif ?>
            <?php if ($module->enableRegistration): ?>
                <p class="text-center">
                    <?= Html::a(Yii::t('user', 'Don\'t have an account? Sign up!'), ['/user/registration/register']) ?>
                </p>
            <?php endif ?>
            <?= Connect::widget([
                'baseAuthUrl' => ['/user/security/auth'],
            ]) ?>
    </div>
</div>
