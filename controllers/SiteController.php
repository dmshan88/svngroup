<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Group;
use app\models\Htpasswd;
use app\models\Changepwd;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SiteController implements the CRUD actions for User model.
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['admin']);
        // return $this->redirect(['changepwd']);
    }
        public function actionChangepwd()
    {
        $model = new Changepwd();
        if ($model->load(Yii::$app->request->post())) {
            $passwd = new Htpasswd(Yii::$app->params['passwdfile']);
            $ok = $passwd->changPasswd($model->name, $model->old_password, $model->new_password);
            if ($ok) {
                return $this->redirect(['index']);
            } else {
                echo "user change password error";
            }
        }

        return $this->render('changepwd', [
            'model' => $model,
        ]);
    }

    public function actionAdmin()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('admin', [
            'dataProvider' => $dataProvider,
            'sort' => [
                'defaultOrder' => [
                    'group_id' => SORT_ASC,
                ]
            ]
        ]);
    }
    public function actionConfig()
    {
        $data=[];
        foreach (User::find()->all() as $user) {
           $data[$user->group_id][] = $user->name;
           $data['all'][] = $user->name;
        }

        $filecontent = "[groups]\n";
        foreach ($data as $groupname => $members) {
            $filecontent .= sprintf("%s = %s\n", $groupname, implode(',', $members));
        }
        $basename = Yii::$app->params['projectbasename'];
        $filecontent .= sprintf("\n[%s:/]\n* = \n", $basename);
        foreach ($data as $groupname => $members) {
            if ($groupname == 'all')
                continue;
            //private
            $filecontent .= sprintf("\n[%s:/%s] \n* = \n", $basename,$groupname);
            $filecontent .= sprintf("@%s = rw\n@all = r\n", $groupname);
            //public
            $filecontent .= sprintf("\n[%s:/%s_public] \n* = \n", $basename,$groupname);
            $filecontent .= sprintf("@%s = rw\n", $groupname);
        }
        if (!file_put_contents(Yii::$app->params['accessfile'], $filecontent) ) {
            echo "config fail";
            echo "<pre>" . $filecontent ."</pre>";
        } else {
            return $this->redirect(['admin']);

        }
    }



    public function actionCreateuser()
    {

        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $passwd = new Htpasswd(Yii::$app->params['passwdfile']);
            $passwd->addUser($model->name, $model->password);
            if ($passwd->doesUserExist($model->name)) {
                return $this->redirect(['admin']);
            } else {
                echo "user add error";
            }
        }

        return $this->render('createuser', [
            'model' => $model,
        ]);
    }

    public function actionCreategroup()
    {
        $model = new Group();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $path = Yii::$app->params['projectpath'].$model->name;
            if (!file_exists($path)) {
                mkdir($path);
                mkdir($path . "_public");
            }
            return $this->redirect(['admin']);
        }

        return $this->render('creategroup', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['admin']);
        }

        return $this->render('updateuser', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $passwd = new Htpasswd(Yii::$app->params['passwdfile']);
        $passwd->deleteUser($model->name);
        $model->delete();

        return $this->redirect(['admin']);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
