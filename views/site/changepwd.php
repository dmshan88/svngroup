<?php

use yii\helpers\Html;
use app\models\Changepwd;
use yii\widgets\ActiveForm;
// use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = '修改密码';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>


</div>
<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])
    	->label("用户名")?>
    <?= $form->field($model, 'old_password')->textInput(['maxlength' => true])
    	->label("旧密码") ?>


    <?= $form->field($model, 'new_password')->textInput(['maxlength' => true])
    	->label("新密码") ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
