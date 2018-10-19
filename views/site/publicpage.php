<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \Yii;
/* @var $this yii\web\View */


$this->title = 'Public Page';
$this->params['breadcrumbs'][] = $this->title;
$testurl = sprintf("%s/%s/test",
                            \Yii::$app->params['svnpath'],
                            \Yii::$app->params['projectbasename']);
?>
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Create User', ['createuser'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Change password', ['changepwd'], ['class' => 'btn btn-danger']) ?>
        <?php //echo Html::a('Admin', ['admin'], ['class' => 'btn btn-info']) ?>
    </p>
<div class="group-index">

<?php //echo "test:" . Html::a($testurl, $testurl) ?>
    <?= GridView::widget([
        'dataProvider' => $userdataProvider,
        'columns' => [
            'department',
            'fullname',
        ],
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $groupdataProvider,
        'columns' => [
            'name',
            [
                'attribute' => 'svn path',
                'format' => 'html',
                'content' => function ($model,$key, $index, $column) {
                    // return $model->group_id; 

                    $url = sprintf("%s/%s/%s",
                            \Yii::$app->params['svnpath'],
                            \Yii::$app->params['projectbasename'],
                            $model->id);
                    return Html::a($url,$url);
                },
            ],
            [
                'attribute' => 'users',
                'format' => 'text',
                'content' => function ($model,$key, $index, $column) {
                    // return $model->group_id; 

                    // $url = sprintf("%s/%s/%s",
                    //         \Yii::$app->params['svnpath'],
                    //         \Yii::$app->params['projectbasename'],
                    //         $model->id);
                    // return Html::a($url,$url);
                    // return $model->
                    $userarr = [];
                    // var_dump($model->getUsers()->all());
                    // var_dump($model->getUsers()->all());
                    foreach ($model->getUsers()->all() as $user) {
                        if (is_object($user)) {
                            $userarr[] = $user->name;
                        }
                    }
                    // var_dump($userarr);
                    return implode(',', $userarr);
                    // return var_dump($userarr);
                },
            ],
        ],
    ]); ?>
</div>
