<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \Yii;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Group Config';
$this->params['breadcrumbs'][] = $this->title;
$testurl = sprintf("%s/%s/test",
                            \Yii::$app->params['svnpath'],
                            \Yii::$app->params['projectbasename']);
?>
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::a('Create Group', ['creategroup'], ['class' => 'btn btn-success']) ?>
    <?= Html::a('bind Group User', ['creategroupuser'], ['class' => 'btn btn-info']) ?>
    <?= Html::a('User Admin', ['useradmin'], ['class' => 'btn btn-warning']) ?>
<div class="group-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'group.name',
            'user.name',
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
            // 'name',
            // [
            //     'attribute' => 'svn path',
            //     'format' => 'html',
            //     'content' => function ($model,$key, $index, $column) {
            //         // return $model->group_id; 

            //         $url = sprintf("%s/%s/%s",
            //                 \Yii::$app->params['svnpath'],
            //                 \Yii::$app->params['projectbasename'],
            //                 $model->id);
            //         return Html::a($url,$url);
            //     },
            // ],
            // [
            //     'attribute' => 'svn path',
            //     'format' => 'html',
            //     'content' => function ($model,$key, $index, $column) {
            //         // return $model->group_id; 

            //         $url = sprintf("%s/%s/%s",
            //                 \Yii::$app->params['svnpath'],
            //                 \Yii::$app->params['projectbasename'],
            //                 $model->id);
            //         return Html::a($url,$url);
            //     },
            // ],
        ],
    ]); ?>
</div>
