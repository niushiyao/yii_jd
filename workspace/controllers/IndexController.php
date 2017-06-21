<?php
/**
 * 网站首页代码
 * @Author niushiyao
 * @Date   2017-06-21
 */
namespace app\controllers;
use yii\web\Controller;
use app\models\Address;

class IndexController extends Controller
{
    /**
     * 网站首页
     * @Author niushiyao
     * @Date   2017-06-21
     */
    //public $layout = false;
    public function actionIndex()
    {
        //$this->layout = false;
        $model = new Address;
        $data = $model->find()->one();
        return $this->renderPartial("index",array('row' => $data));
    }
}