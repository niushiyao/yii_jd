<?php
/**
 * 购物车页面
 * @Author niushiyao
 * @Date   2017-06-21
 */
namespace app\controllers;
use yii\web\Controller;

class CartController extends Controller
{
    /**
     * 购物车页面
     * @Author niushiyao
     * @Date   2017-06-21
     */            
     public function actionIndex()
    {
        $this->layout = 'layout1';
        return $this->render('index');
    }
     
}