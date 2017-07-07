<?php
/**
 * 网站首页代码
 * @Author niushiyao
 * @Date   2017-06-21
 */
namespace app\controllers;
use yii\web\Controller;
use app\models\Address;
use app\controllers\Commoncontroller;

class IndexController extends Controller
{
    /**
     * 网站首页
     * @Author niushiyao
     * @Date   2017-06-21
     */
    public function actionIndex()
    {
        $this->layout = 'layout1';
        return $this->render("index");
    }
}