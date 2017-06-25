<?php
namespace app\modules\controllers;
use yii\web\Controller;
use app\modules\models\Admin;
use Yii;

class PublicController extends Controller
{
    public function actionLogin()
    {
        $this->layout = false;
        $admin_model = new Admin;
        if(Yii::$app->request->isPost)
        {
            $loginInfo = Yii::$app->request->post();
            if($admin_model->login($loginInfo))
            {
                $this->redirect(['default/index']);
                Yii::$app->end();
            }
        }
        
        return $this->render('login',array('model' => $admin_model));
    }
    
    /**
     * 退出登录
     * @author niushiyao
     * @date   2017-06-25
     */
    public function actionLogout()
    {
        Yii::$app->session->removeAll();
        if(!isset(Yii::$app->session['admin']['isLogin']))
        {
            $this->redirect(['public/login']);
            Yii::$app->end();
        }
        $this->goback();
    }
    
    /**
     * 找回密码功能
     */
     public function actionSeekpassword()
     {
         $this->layout = false;
         $admin_model = new Admin;
         if(Yii::$app->request->isPost)
         {
             $userInfo = Yii::$app->request->post();
             if($admin_model->seekPass($userInfo))
             {
                 Yii::$app->session->setFlash('info','电离邮件已经发送成功，请查收');
             }
         }
         return $this->render('seekpassword',['model' => $admin_model]);
     }
    
}