<?php
namespace app\modules\controllers;
use yii\web\Controller;
use app\modules\models\Admin;
use Yii;
use yii\data\Pagination;

class ManageController extends Controller
{
    public function actionMailchangepass()
    {
        $this->layout = false;
        $time = Yii::$app->request->get("timestamp");
        $adminuser = Yii::$app->request->get("adminuser");
        $token = Yii::$app->request->get("token");
        $admin_model = new Admin;
        $myToken = $admin_model->createToken($adminuser,$time);
        if($token != $myToken)
        {
            $this->redirect(['public/login']);
            Yii::$app->end();
        }
        /*if(time() - $time > 1800)
        {
            $this->redirect(['public/login']);
            Yii::$app->end();
        }*/
        
        if(Yii::$app->request->isPost)
        {
            $postData = Yii::$app->request->post();
            if($admin_model->changePass($postData))
            {
                Yii::$app->session->setFlash('info','密码修改成功');
            }
        }
        
        $admin_model->adminuser = $adminuser;
        return $this->render('mailchangepass',['model' => $admin_model]);
    }
    
    /**
     * 管理员列表
     * @author niushiyao
     * @date   2017-06-25
     */
     public function actionManagers()
     {
         $this->layout = 'layout1';
         $admin_model = Admin::find();
         $count = $admin_model->count();
         $pageSize = Yii::$app->params['pageSize']['manage'];
         $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
         $managers = $admin_model->offset($pager->offset)->limit($pager->limit)->orderBy('adminid desc')->all();
         return $this->render('managers',['managers' => $managers,'pager' => $pager]);
     }
      
    /**
     * 添加管理员
     * @author niushiyao
     * @date   2017-06-25
     */
     public function actionReg()
     {
        $this->layout = 'layout1';
        $admin_model = new Admin;
        if(Yii::$app->request->isPost)
        {
            $post = Yii::$app->request->post();
            if($admin_model->reg($post))
            {
                Yii::$app->session->setFlash('info','添加成功');
            }else{
                Yii::$app->session->setFlash('info','添加失败');
            }
        }
        $admin_model->adminpass = '';
        $admin_model->repass = '';
        return $this->render('reg',['model' => $admin_model]);
     }
    
    /**
     * 删除用户
     */
     public function actionDel()
     {
         $adminid = (int)Yii::$app->request->get("adminid");
         if(empty($adminid))
         {
             $this->redirect(['manage/managers']);
         }
         $admin_model = new Admin;
         if($admin_model->deleteAll('adminid = :id',[':id' => $adminid]))
         {
             Yii::$app->session->setFlash('info','删除成功');
             $this->redirect(['manage/managers']);
         }
     }
     
     /**
      * 个人信息管理
      * @author niushiyao
      * @date   2017-06-26
      */
      public function actionChangeemail()
      {
          $this->layout = 'layout1';
          $model = Admin::find()->where('adminuser = :user',[':user' => Yii::$app->session['admin']['adminuser']])->one();
          if(Yii::$app->request->isPost)
          {
              $post = Yii::$app->request->post();
              if($model->changeemail($post))
              {
                  Yii::$app->session->setFlash('info','修改成功');
              }
          }
          $model->adminpass = '';
          return $this->render('changeemail',['model' => $model]);
      }
      
      /**
       * 修改密码
       * @author niushiyao
       * @date   2017-06-26
       */
       public function actionChangepass()
       {
           $this->layout = 'layout1';
           $model = Admin::find()->where("adminuser = :user",[':user' => Yii::$app->session['admin']['adminuser']])->one();
           if(Yii::$app->request->isPost)
           {
               $post = Yii::$app->request->post();
               if($model->changepass($post))
               {
                   Yii::$app->session->setFlash('info','修改成功');
               }
           }
           $model->adminpass = '';
           $model->repass = '';
           return $this->render('changepass',['model' => $model]);
       }
}