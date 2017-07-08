<?php
/**
*商品相关页面
* @Author niushiyao
* @Date   2017-06-21
*/
namespace app\controllers;
use yii\web\Controller;
use app\controllers\CommonController;

class ProductController extends CommonController
{
    //public $layout = false;
    /**
     * 商品列表页面
     * @Author niushiyao
     * @Date   2017-06-21
     */
    public function actionIndex()
    {
        $this->layout = 'layout2';
        return $this->render('index');
    }
    
    /**
     * 商品详情页面
     * @Author niushiyao
     * @Date   2017-06-21
     */
     public function actionDetail()
     {
         $this->layout = 'layout2';
         return $this->render('detail');
     }
    
    
}