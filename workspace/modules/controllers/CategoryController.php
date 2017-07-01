<?php
namespace app\modules\controllers;
use yii\web\Controller;
use app\modules\models\Category;
use Yii;

class CategoryController extends Controller
{
    /**
     * 分类列表
     * @date 2017-07-01
     */
    public function actionList()
    {
        $this->layout = 'layout1';
        return $this->render('cates');
    }
    
    /**
     * 分类添加
     * @date 2017-07-01
     */
    public function actionAdd()
    {
        $this->layout = 'layout1';
        $model = new Category;
        $list = $model->getOptions();
        if(Yii::$app->request->isPost)
        {
            $post = Yii::$app->request->post();
            if($model->add($post))
            {
                Yii::$app->session->setFlash('info','添加成功');
            }
        }
        return $this->render('add',['model' => $model,'list' => $list]);
    }
    
    

}