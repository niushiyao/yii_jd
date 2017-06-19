<?php
namespace app\controllers;
use yii\web\Controller;
use app\models\Address;

class IndexController extends Controller
{
    public function actionIndex()
    {
        $model = new Address;
        $data = $model->find()->one();
        return $this->render("index",array('row' => $data));
    }
}