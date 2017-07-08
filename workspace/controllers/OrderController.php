<?php
/**
 * @Author niushiyao
 * @Date   2017-06-21
 */
 namespace app\controllers;
 use yii\web\Controller;
 use app\controllers\CommonController;
 
 class OrderController extends CommonController
 {
     /**
      * 订单中心页面
      * @Author niushiyao
      * @Date   2017-06-21
      */
      public function actionIndex()
      {
        $this->layout = 'layout2';
        return $this->render('index');  
      }
      
     /**
      * 收银台页面
      * @Author niushiyao
      * @Date   2017-06-21
      */ 
     public function actionCheck()
     {
         $this->layout = 'layout1';
         return $this->render('check');
     }
 }