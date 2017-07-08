<?php
/**
 * @Author niushiyao
 * @Date   2017-06-21
 */
 namespace app\controllers;
 use yii\web\Controller;
 use app\models\User;
 use Yii;
 use app\controllers\CommonController;
 
 class MemberController extends CommonController
 {
     /**
      * 用户注册登录页面
      * @Author niushiyao
      * @Date   2017-06-21
      */
      public function actionAuth()
      {
          $this->layout = 'layout2';
          $model = new User;
          if(Yii::$app->request->isPost)
          {
              $post = Yii::$app->request->post();
              if($model->login($post))
              {
                  return $this->goBack(Yii::$app->request->referrer);
              }
          }
          return $this->render('auth',['model' => $model]);
      }
      
      /**
       * 退出登录
       * @date  2017-06-28
       */
       public function actionLogout()
       {
            Yii::$app->session->remove('loginname');
            Yii::$app->session->remove('isLogin');
            if(!isset(Yii::$app->session['isLogin']))
            {
                return $this->goBack(Yii::$app->request->referrer);
            }
       }
       
       /**
        * 注册
        * @date  2017-06-28
        */
        public function actionReg()
        {
            $model = new User;
            if(Yii::$app->request->isPost)
            {
                $post = Yii::$app->request->post();
                if($model->regByMail($post))
                {
                    Yii::$app->session->setFlash('info','电子邮件发送成功');
                }
            }
            $this->layout = 'layout2';
            return $this->render('auth',['model' => $model]);
        }
       
       /**
        * qq登录
        */
        public function actionQqlogin()
        {
            require_once("../vendor/qqlogin/qqConnectAPI.php");
            $qc = new \QC();
            $qc->qq_login();
        }
        
        /**
         * QQ回调函数
         */
         public function actionQqcallback()
         {
             require_once("../vendor/qqlogin/qqConnectAPI.php");
             $auth = new \OAuth();
             $accessToken = $auth->qq_callback();
             $openid = $auth->get_openid();
             $qc = new \QC($accessToken,$openid);
             $userinfo = $qc->get_user_info();
             $session = Yii::$app->session();
             $session['userinfo'] = $userinfo;
             $session['openid'] = $openid;
             if(User::find()->where('openid = :openid',[':openid' => $openid])->one())
             {
                 $session['loginname'] = $userinfo['nickname'];
                 $session['isLogin'] = 1;
                 return $this->redirect(['member/qqreg']);
             }
             return $this->redirect(['member/qqreg']);
         }
        
        /**
         * QQ登录注册
         */
         public function actionQqreg()
         {
             $this->layout = 'layout2';
             $model = new User;
             if(Yii::$app->request->isPost)
             {
                 $post = Yii::$app->request->post();
                 $session = Yii::$app->session;
                 $post['User']['openid'] = $session['openid'];
                 if($model->reg($post,'qqreg'))
                 {
                     $session['loginname'] = $session['userinfo']['nickname'];
                     $session['isLogin'] = 1;
                     return $this->redirect(['index/index']);
                 }
             }
             return $this->render('qqreg', ['model' => $model]);
         }
     
 }