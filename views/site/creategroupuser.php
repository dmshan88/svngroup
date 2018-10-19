<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Group;
use app\models\User;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Group */

$this->title = 'Create Group User';
$this->params['breadcrumbs'][] = ['label' => 'Admin', 'url' => ['admin']];
$this->params['breadcrumbs'][] = $this->title;
$grouparr = Group::find()->select(['id','name'])->asArray()->all();
$userarr = User::find()->select(['id','name'])->asArray()->all();
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'group_id')
    	->dropdownList(ArrayHelper::map($grouparr,'id', 'name')); ?>

    <?= $form->field($model, 'user_id')
    	->dropdownList(ArrayHelper::map($userarr,'id', 'name')); ?>


    <div class="form-groupuser">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
