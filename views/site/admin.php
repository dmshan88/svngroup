<?php

use yii\helpers\Html;
use yii\grid\GridView;
use Yii;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Group', ['creategroup'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Create User', ['createuser'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Config', ['config'], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Change password', ['changepwd'], ['class' => 'btn btn-danger']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            // 'name',
            // 'group_id.name',
            [
                'attribute' => 'group',
                'format' => 'text',
                'content' => function ($model,$key, $index, $column) {
                    return $model->group->name; 
                },
            ],
            'fullname',
            [
                'attribute' => 'svn path',
                'format' => 'html',
                'content' => function ($model,$key, $index, $column) {
                    // return $model->group_id; 
                    $url = sprintf("%s%s/%s",
                            Yii::$app->params['svnpath'],
                            Yii::$app->params['projectbasename'],
                            $model->group_id);
                    $publc_url = $url . "_public";
                    return "private: " .Html::a($url,$url) ."<br>public: ". Html::a($publc_url,$publc_url) ;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
</div>
