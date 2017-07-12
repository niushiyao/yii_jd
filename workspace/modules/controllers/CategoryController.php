<?php
namespace app\modules\controllers;
use yii\web\Controller;
use app\modules\models\Category;
use Yii;
use app\modules\controllers\CommonController;

class CategoryController extends CommonController
{
    /**
     * 分类列表
     * @date 2017-07-01
     */
    public function actionList()
    {
        $this->layout = 'layout1';
        $model = new Category;
        $cates = $model->getTreeList();
        return $this->render('cates',['cates' => $cates]);
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
    
    /**
     * 修改
     */
     public function actionMod()
     {
         $this->layout = 'layout1';
         $cateid = Yii::$app->request->get('cateid');
         $model = Category::find()->where('cateid = :id',[':id' => $cateid])->one();
         if(Yii::$app->request->isPost)
         {
             $post = Yii::$app->request->post();
             if($model->load($post) && $model->save())
             {
                 Yii::$app->session->setFlash('info','修改成功');
             }
         }
         $list = $model->getOptions();
         return $this->render('add',['model' => $model,'list' => $list]);
     }
     
     /**
      * 删除
      */
      public function actionDel()
      {
        try {
             $cateid = (int)Yii::$app->request->get('cateid');
             if(empty($cateid))
             {
                 throw new \Exception('参数错误');
             }
             $data = Category::find()->where('parentid = :pid',[':pid' => $cateid])->one();
             if($data)
             {
                 throw new \Exception('该分类下有子类，不允许删除');
             }
             
             if(!Category::deleteAll('cateid = :id',[':id' => $cateid]))
             {
                 throw new \Exception('删除失败');
             }
         
         }
         catch(\Exception  $e)
         {
             Yii::$app->session->setFlash('info',$e->getMessage());
         }
         return $this->redirect(['category/list']);
         
      }

}