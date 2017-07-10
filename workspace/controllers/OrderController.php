<?php
/**
 * @Author niushiyao
 * @Date   2017-06-21
 */
 namespace app\controllers;
 use app\controllers\CommonController;
 use Yii;
 use app\modules\models\Order;
 use app\modules\models\OrderDetail;
 use app\modules\models\Cart;
 use app\modules\models\Product;
 use app\modules\models\User;
 use app\models\Address;
 use app\models\Pay;
 use dzer\express\Express;

 class OrderController extends CommonController
 {
     /**
      * 订单中心页面
      * @Author niushiyao
      * @Date   2017-06-21
      */
      public function actionIndex()
      {
        $this->layout = 'layout2';
        if(Yii::$app->session['isLogin'] != 1)
        {
            return $this->redirect(['member/auth']);
        }
        $loginname = Yii::$app->session['loginname'];
        $userid = User::find()->where('username = :name or useremail = :email',[':name' => $loginname, ':email' => $loginname])->one()->userid;
        $orders = Order::getProducts($userid);
        return $this->render('index',['orders' => $orders]);  
      }
      
     /**
      * 收银台页面
      * @Author niushiyao
      * @Date   2017-06-21
      */ 
     public function actionCheck()
     {
         $this->layout = 'layout1';
         if(Yii::$app->session['isLogin'] !=1)
         {
             return $this->redirect(['member/auth']);
         }
         $orderid = Yii::$app->request->get('orderid');
         $status  = Order::find()->where('orderid = :oid',[':oid' => $orderid])->one()->status;
         if($status != Order::CREATEORDER && $status != Order::CHECKORDER)
         {
             return $this->redirect(['order/index']);
         }
         $loginname = Yii::$app->session['loginname'];
         $userid = User::find()->where('username = :name or useremail = :email',[':name' => $loginname, ':email' => $loginname])->one()->userid;
         $addresses = Address::find()->where('userid = :uid',[':uid' => $userid])->asArray()->all();
         $details = OrderDetail::find()->where('orderid = :oid', [':oid' => $orderid])->asArray()->all();
         $data = [];
         foreach($details as $detail)
         {
             $model = Product::find()->where('productid = :pid', [':pid' => $detail['productid']])->one();
             $detail['title'] = $model->title;
             $detail['cover'] = $model->cover;
             $data[] = $detail;
         }
         $express = Yii::$app->params['express'];
         $expressPrice = Yii::$app->params['expressPrice'];
         return $this->render('check',['express' => $express, 'expressPrice' => $expressPrice, 'addresses' => $addresses, 'products' => $data]);
     }
     
     /**
      * 添加订单
      */
      public function actionAdd()
      {
          if(Yii::$app->session['isLogin'] != 1)
          {
              return $this->redirect(['member/auth']);
          }
          $transaction = Yii::$app->db->beginTransaction();
          try{
              if(Yii::$app->request->isPost)
              {
                  $post = Yii::$app->request->post();
                  $ordermodel = new Order;
                  $ordermodel->scenario = 'add';
                  $usermodel = User::find()->where('username = :name or useremail = :email',[':name' => Yii::$app->session['loginname'], ':email' => Yii::$app->session['loginname']])->one();
                  if(!$usermodel)
                  {
                      throw new \Exception();
                  }
                  $userid = $usermodel->userid;
                  $ordermodel->userid = $userid;
                  $ordermodel->status = Order::CREATEORDER;
                  $ordermodel->createtime = time();
                  
                  if(!$ordermodel->save())
                  {
                      throw new \Exception();
                  }
                  
                  $orderid = $ordermodel->getPrimaryKey();
                  foreach($post['OrderDetail'] as $product)
                  {
                      $model = new OrderDetail;
                      $product['orderid'] = $orderid;
                      $product['createtime'] = time();
                      $data['OrderDetail'] = $product;
                      if(!$model->add($data))
                      {
                          throw new \Exception();
                      }
                      Cart::deleteAll('productid = :pid',[':pid' => $product['productid']]);
                      Product::updateAllCounters(['num' => $product['productnum']], 'productid = :pid',[':pid' => $product['productid']]);
                  }
              }
              $transaction->commit();
          }
          catch(\Exception $e)
          {
              echo $e->getMessage();exit;
              $transaction->rollback();
              return $this->redirect(['cart/index']);
          }
          return $this->redirect(['order/check', 'orderid' => $orderid]);
      }
    
    /**
     * 订单确认方法
     * @date  2017-07-09
     */
     public function actionConfirm()
     {
         try{
             if(Yii::$app->session['isLogin'] != 1)
             {
                 return $this->redirect(['member/auth']);
             }
             if(!Yii::$app->request->isPost)
             {
                 throw new \Exception();
             }
             $post = Yii::$app->request->post();
             $loginname = Yii::$app->session['loginname'];
             $usermodel = User::find()->where('username = :name or useremail = :email',[':name' => $loginname, ':email' => $loginname])->one();
             if(empty($usermodel))
             {
                 throw new \Exception();
             }
             $userid = $usermodel->userid;
             $model = Order::find()->where('orderid = :oid and userid = :uid', [':oid' => $post['orderid'], ':uid' => $userid])->one();
             if (empty($model)) {
                throw new \Exception();
             }
             $model->scenario = 'update';
             $post['status'] = Order::CHECKORDER;
             $details = OrderDetail::find()->where('orderid = :oid',[':oid' => $post['orderid']])->all();
             $amount = 0;
             foreach($details as $detail)
             {
                 $amount+= $detail->productnum*$detail->price;
             }
             if($amount<=0)
             {
                 throw new \Exception();
             }
             $express = Yii::$app->params['expressPrice'][$post['expressid']];
             if($express < 0)
             {
                 throw new \Exception();
             }
             $amount+=$express;
             $post['amount'] = $amount;
             $data['Order'] = $post;
             if($model->load($data) && $model->save())
             {
                 return $this->redirect(['order/pay','orderid' => $post['orderid'], 'paymethod' => $post['paymethod']]);
             }
         }catch(\Exception $e){
             return $this->redirect(['index/index']);
         }
     }
    
    /**
     * 支付方法
     */
     public function actionPay()
     {
         try{
             if(Yii::$app->session['isLogin'] != 1)
             {
                 throw new \Exception();
             }
             $orderid = Yii::$app->request->get('orderid');
             $paymethod = Yii::$app->request->get('paymethod');
             if(empty($orderid) || empty($paymethod))
             {
                 throw new \Exception();
             }
             if($paymethod == 'alipay')
             {
                 return Pay::alipay($orderid);
             }
         }catch(\Exception $e)
         {
             return $this->redirect(['order/index']);
         }
     }
    
    /**
     * 获得快递信息
     */
     public function actionGetexpress()
     {
         $expressno = Yii::$app->request->get('expressno');
         $res = Express::search($expressno);
         exit($res);
     }
    
    /**
     * 确认收货
     */
     public function actionReceived()
     {
         $orderid = Yii::$app->request->get('orderid');
         $order = Order::find()->where('orderid = :oid',[':oid' => $orderid])->one();
         if(!empty($order) && $order>status == Order::SENDED)
         {
             $order->status = Order::RECEIVED;
             $order->save();
         }
         return $this->redirect(['order/index']);
     }

    
 }