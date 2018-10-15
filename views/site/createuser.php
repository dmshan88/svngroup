<?php

use yii\helpers\Html;
use app\models\Group;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = $this->title;
$grouparr = Group::find()->select(['id','name'])->asArray()->all();
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>


</div>
<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'group_id')
				    ->dropdownList(ArrayHelper::map($grouparr,'id', 'name'));
    ?>

    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
