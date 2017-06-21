<?php
/**
 * @Author niushiyao
 * @Date   2017-06-21
 */
 namespace app\controllers;
 use yii\web\Controller;
 
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
          return $this->render('auth');
      }
 }