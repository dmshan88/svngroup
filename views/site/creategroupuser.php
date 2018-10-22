<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Group;
use app\models\User;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Group */

$this->title = '分配权限';
$this->params['breadcrumbs'][] = ['label' => '分组管理', 'url' => ['admin']];
$this->params['breadcrumbs'][] = $this->title;
$grouparr = Group::find()->select(['id','name'])->asArray()->all();
$userarr = User::find()->select(['id','fullname'])->asArray()->all();
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'group_id')
    	->dropdownList(ArrayHelper::map($grouparr,'id', 'name'))
        ->label("分组"); ?>

    <?= $form->field($model, 'user_id')
    	->dropdownList(ArrayHelper::map($userarr,'id', 'fullname'))
        ->label("用户"); ?>


    <div class="form-groupuser">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
