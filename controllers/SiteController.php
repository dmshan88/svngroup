<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Group;
use app\models\Groupuser;
use app\models\Htpasswd;
use app\models\Changepwd;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
// use yii\filters\VerbFilter;

/**
 * SiteController implements the CRUD actions for User model.
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    // public function behaviors()
    // {
    //     return [
    //         'verbs' => [
    //             'class' => VerbFilter::className(),
    //             'actions' => [
    //                 'delete' => ['POST'],
    //             ],
    //         ],
    //     ];
    // }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['publicpage']);
        // return $this->redirect(['changepwd']);
    }
    public function actionPublicpage()
    {
        $userdataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'sort' => [
                'defaultOrder' => [
                    'department' => SORT_ASC,
                ]
            ]
        ]);
        $groupdataProvider = new ActiveDataProvider([
            'query' => Group::find(),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ]
        ]);
        
        return $this->render('publicpage',[
            'groupdataProvider' => $groupdataProvider,
            'userdataProvider' => $userdataProvider,
        ]); 
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
            'query' => Groupuser::find(),
            'sort' => [
                'defaultOrder' => [
                    'group_id' => SORT_ASC,
                ]
            ]
        ]);

        return $this->render('admin', ['dataProvider' => $dataProvider ]);
    }
    public function actionUseradmin()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'sort' => [
                'defaultOrder' => [
                    'department' => SORT_ASC,
                ]
            ]
        ]);

        return $this->render('useradmin', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateuser()
    {

        $model = new User();

        if ($model->load(Yii::$app->request->post()) ) {
            $clearpasswd = $model->password;
            $model->password = base64_encode(sha1($clearpasswd, true)); 
            if ($model->save()) {
                $passwd = new Htpasswd(Yii::$app->params['passwdfile']);
                $passwd->addUser($model->name, $model->password);
                if ($passwd->doesUserExist($model->name)) {
                    return $this->redirect(['index']);
                } else {
                    echo "user add error";
                }
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
            return $this->redirect(['admin']);
        }

        return $this->render('creategroup', [
            'model' => $model,
        ]);
    }

    public function actionCreategroupuser()
    {
        $model = new Groupuser();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (configauth())
                return $this->redirect(['admin']);
            else
                return "config error";
        }

        return $this->render('creategroupuser', [
            'model' => $model,
        ]);
    }

    public function actionDeleteauth($group_id,$user_id) 
    {
        $model = Groupuser::findOne(['group_id' => $group_id,'user_id' => $user_id,]);
        if (is_null($model))
            return "error";
        $model->delete();
         if (configauth())
            return $this->redirect(['admin']);
        else
            return "config error";
    }

    public function actionDelete($id)
    {
        $model = User::findOne($id);
        if (is_null($model))
            return "error";
        $passwd = new Htpasswd(Yii::$app->params['passwdfile']);
        $passwd->deleteUser($model->name);

        Groupuser::deleteAll(["user_id" => $id]);
        $model->delete();
        if (configauth())
            return $this->redirect(['useradmin']);
        else
            return "config error";
    }

}
function configauth()
{
    $data=[];
    $all = [];
    foreach (Groupuser::find()->all() as $groupuser) {
        $username = $groupuser->user->name;

        $data[$groupuser->group_id][] = $username;
        $all[] = $username;
    }
    $data['all'] = array_unique($all);

    $filecontent = "[groups]\n";
    foreach ($data as $groupname => $members) {
        $filecontent .= sprintf("%s = %s\n", $groupname, implode(',', $members));
    }
    $basename = Yii::$app->params['projectbasename'];
    $filecontent .= sprintf("\n[%s:/]\n* = \n@project = rw \n", $basename);
    foreach ($data as $groupname => $members) {
        if ($groupname == 'all'){
            $filecontent .= sprintf("\n[%s:/%s] \n* = \n", $basename,'test');
            $filecontent .= sprintf("@all = rw\n", $groupname);
            continue;
        }
        //private
        $filecontent .= sprintf("\n[%s:/%s] \n* = \n", $basename,$groupname);
        $filecontent .= sprintf("@%s = rw\n@all = r\n", $groupname);
        //public
        // $filecontent .= sprintf("\n[%s:/%s_public] \n* = \n", $basename,$groupname);
        $filecontent .= sprintf("@%s = rw\n", $groupname);
    }
    return file_put_contents(Yii::$app->params['accessfile'], $filecontent);
}
