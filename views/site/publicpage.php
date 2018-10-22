<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \Yii;
/* @var $this yii\web\View */


$this->title = 'SVN管理系统';
$testurl = sprintf("%s/%s/test",
                            \Yii::$app->params['svnpath'],
                            \Yii::$app->params['projectbasename']);
?>
    <h1><?php echo Html::encode($this->title); ?></h1>
    <p>
        <?= Html::a('新建用户', ['createuser'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('修改密码', ['changepwd'], ['class' => 'btn btn-danger']) ?>
        <?php echo Html::a('管理员', ['admin'], ['class' => 'btn btn-info']) ?>
    </p>
<div class="group-index">

<?php echo "测试路径:" . Html::a($testurl, $testurl) ?>
<br>
<?php echo "下载软件:" . Html::a('TortosiseSVN', "https://tortoisesvn.net/downloads.html") ?>
<br>用户列表:
    <?= GridView::widget([
        'dataProvider' => $userdataProvider,
        'columns' => [
            'department',
            'fullname',
        ],
    ]); ?>
<br>项目列表:
    <?= GridView::widget([
        'dataProvider' => $groupdataProvider,
        'columns' => [
            'name',
            [
                'attribute' => 'svn路径',
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
                'attribute' => '使用者',
                'format' => 'text',
                'content' => function ($model,$key, $index, $column) {
                    $userarr = [];

                    foreach ($model->getUsers()->all() as $user) {
                        if (is_object($user)) {
                            $userarr[] = $user->fullname;
                        }
                    }
                    return implode(',', $userarr);
                },
            ],
        ],
    ]); ?>
</div>
