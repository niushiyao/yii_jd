<?php
/**
 * @Author niushiyao
 * @Date   2017-06-21
 */
 namespace app\controllers;
 use yii\web\Controller;
 use app\models\User;
 use Yii;
 
 class MemberController extends Controller
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
       
       
       
 }