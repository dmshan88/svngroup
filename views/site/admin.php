<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \Yii;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分组管理';
$this->params['breadcrumbs'][] = $this->title;
$testurl = sprintf("%s/%s/test",
                            \Yii::$app->params['svnpath'],
                            \Yii::$app->params['projectbasename']);
?>
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::a('新建分组', ['creategroup'], ['class' => 'btn btn-success']) ?>
    <?= Html::a('分配权限', ['creategroupuser'], ['class' => 'btn btn-info']) ?>
    <?= Html::a('用户管理', ['useradmin'], ['class' => 'btn btn-warning']) ?>
<div class="group-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'group.name',
            'user.fullname',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{deleteauth}',
                "buttons" => [
                    'deleteauth' => function ($url, $model, $key) {
                        // return the button HTML code
                        return Html::a('Delete', $url);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
