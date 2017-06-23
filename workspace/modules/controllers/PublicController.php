<?php
namespace app\modules\controllers;
use yii\web\Controller;
use app\modules\models\Admin;

class PublicController extends Controller
{
    public function actionLogin()
    {
        $this->layout = false;
        $admin_model = new Admin;
        return $this->render('login',array('model' => $model));
    }
}