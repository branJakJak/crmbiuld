<?php


/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\User $user
 * @var \yii\rbac\Role $currentRole
 */
use yii\helpers\Html;

$availableRoles = [
    'Admin' => 'Admin',
    'Senior Manager' => 'Senior Manager',
    'Manager' => 'Manager',
    'Consultant' => 'Consultant',
    'Agent' => 'Agent'
];
if (Yii::$app->user->can('Agent')) {
    $availableRoles = [
        'Consultant' => 'Consultant',
    ];
}
if (Yii::$app->user->can('Senior Manager')) {
    $availableRoles = [
        'Manager' => 'Manager',
    ];
}
$defaultSelectedRole = 'Agent';
if (Yii::$app->user->can('Manager')) {
    $availableRoles = [
        'Agent' => 'Agent',
        'Consultant' => 'Consultant'
    ];


}


$roles = Yii::$app->authManager->getRolesByUser($user->id);


if (count($roles) >= 1) {
    $currentRole = reset($roles);
    $defaultSelectedRole = $currentRole->name;
}


?>

<?= $form->field($user, 'email')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'username')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'password')->passwordInput() ?>
<div class="form-group field-user-password">
    <label class="control-label col-sm-3" for="user-password">Role</label>
    <div class="col-sm-9">
        <?= Html::dropDownList('role', $defaultSelectedRole, $availableRoles, ['class' => 'form-control']); ?>
    </div>

</div>

