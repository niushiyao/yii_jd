<?php
/**
*商品相关页面
* @Author niushiyao
* @Date   2017-06-21
*/
namespace app\controllers;
use yii\web\Controller;

class ProductController extends Controller
{
    //public $layout = false;
    /**
     * 商品列表页面
     * @Author niushiyao
     * @Date   2017-06-21
     */
    public function actionIndex()
    {
        return $this->renderPartial('index');
    }
    
    /**
     * 商品详情页面
     * @Author niushiyao
     * @Date   2017-06-21
     */
     public function actionDetail()
     {
         return $this->renderPartial('detail');
     }
    
    
}