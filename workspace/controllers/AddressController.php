<?php
namespace app\controllers;
use app\controllers\CommonController;
use Yii;
use app\modules\models\User;
use app\models\Address;

class AddressController extends CommonController
{
    /**
     * 增加地址信息
     */
     public function actionAdd()
     {
         if(Yii::$app->session['isLogin'] != 1)
         {
             return $this->redirect(['member/auth']);
         }
         $loginname = Yii::$app->session['loginname'];
         $userid = User::find()->where('username = :name or useremail = :email',[':name' => $loginname, ':email' => $loginname])->one()->userid;
         if(Yii::$app->request->isPost)
         {
             $post = Yii::$app->request->post();
             $post['userid'] = $userid;
             $post['address'] = $post['address1'].$post['address2'];
             $data['Address'] = $post;
             $model = new Address;
             $model->load($data);
             $model->save();
         }
         return $this->redirect($_SERVER['HTTP_REFERER']);
     }
    
    /**
     * 删除常用信息
     */
     public function actionDel()
     {
         if(Yii::$app->session['isLogin'] != 1)
         {
             return $this->redirect(['member/auth']);
         }
         $loginname = Yii::$app->session['loginname'];
         $userid = User::find()->where('username = :name or useremail = :email',[':name' => $loginname, ':email' => $loginname])->one()->userid;
         $addressid = Yii::$app->request->get('addressid');
         $res = Address::find()->where('userid = :uid and addressid = :aid',[':uid' => $userid, ':aid' => $addressid])->one();
         if(!$res)
         {
             return $this->redirect($_SERVER['HTTP_REFERER']);
         }
         Address::deleteAll('addressid = :aid',[':aid' => $addressid]);
         return $this->redirect($_SERVER['HTTP_REFERER']);
     }
    
}