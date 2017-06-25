<?php
namespace app\modules\controllers;
use yii\web\Controller;
use app\modules\models\Admin;
use Yii;

class ManageController extends Controller
{
    public function actionMailchangepass()
    {
        $this->layout = false;
        $time = Yii::$app->request->get("timestamp");
        $adminuser = Yii::$app->request->get("adminuser");
        $token = Yii::$app->request->get("token");
        $admin_model = new Admin;
        $myToken = $admin_model->createToken($adminuser,$time);
        if($token != $myToken)
        {
            $this->redirect(['public/login']);
            Yii::$app->end();
        }
        /*if(time() - $time > 1800)
        {
            $this->redirect(['public/login']);
            Yii::$app->end();
        }*/
        
        if(Yii::$app->request->isPost)
        {
            $postData = Yii::$app->request->post();
            if($admin_model->changePass($postData))
            {
                Yii::$app->session->setFlash('info','密码修改成功');
            }
        }
        
        $admin_model->adminuser = $adminuser;
        return $this->render('mailchangepass',['model' => $admin_model]);
    }
}