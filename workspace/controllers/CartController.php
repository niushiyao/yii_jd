<?php
/**
 * 购物车页面
 * @Author niushiyao
 * @Date   2017-06-21
 */
namespace app\controllers;
use app\controllers\CommonController;
use app\modules\models\User;
use app\modules\models\Cart;
use app\modules\models\Product;
use Yii;

class CartController extends CommonController
{
    /**
     * 购物车页面
     * @Author niushiyao
     * @Date   2017-06-21
     */            
     public function actionIndex()
    {
        if(Yii::$app->session['isLogin'] != 1)
        {
            return $this->redirect(['member/auth']);
        }
        $userid = User::find()->where('username = :name',[':name' => Yii::$app->session['loginname']])->one()->userid;
        $cart = Cart::find()->where('userid = :uid',[':uid' => $userid])->asArray()->all();
        $data = [];
        foreach($cart as $k => $pro)
        {
            $product = Product::find()->where('productid = :pid',[':pid' => $pro['productid']])->one();
            $data[$k]['cover'] = $product->cover;
            $data[$k]['title'] = $product->title;
            $data[$k]['productnum'] = $pro['productnum'];
            $data[$k]['price'] = $pro['price'];
            $data[$k]['productid'] = $pro['productid'];
            $data[$k]['cartid'] = $pro['cartid'];
        }
        $this->layout = 'layout1';
        return $this->render('index',['data' => $data]);
    }
    
    /**
     * 添加到购物车
     * @date 2017-07-08
     */
     public function actionAdd()
    {
        if(Yii::$app->session['isLogin'] != 1)
        {
            return $this->redirect(['member/auth']);
        }
        $userid = User::find()->where('username = :name', [':name' => Yii::$app->session['loginname']])->one()->userid;
        if(Yii::$app->request->isPost)
        {
            $post = Yii::$app->request->post();
            $num = Yii::$app->request->post()['productnum'];
            $data['Cart'] = $post;
            $data['Cart']['userid'] = $userid;
        }
        if(Yii::$app->request->isGet)
        {
            $productid = Yii::$app->request->get('productid');
            $model = Product::find()->where('productid = :pid',[':pid' => $productid])->one();
            $price = $model->issale ? $model->saleprice : $model->price;
            $num = 1;
            $data['Cart'] = ['productid' => $productid, 'productnum' => $num, 'price' => $price, 'userid' => $userid];
        }
        if(!$model = Cart::find()->where('productid = :pid and userid = :uid',[':pid' => $data['Cart']['productid'], ':uid' => $data['Cart']['userid']])->one())
        {
            $model = new Cart;
        }else{
            $data['Cart']['productnum'] = $model->productnum + $num;
        }
        $data['Cart']['createtime'] = time();
        $model->load($data);
        $model->save();
        return $this->redirect(['cart/index']);
    }

    /**
     * 修改购物车
     */
     public function actionMod()
     {
        $cartid = Yii::$app->request->get('cartid');
        $productnum = Yii::$app->request->get('productnum');
        Cart::updateAll(['productnum' => $productnum],'cartid = :cid', [':cid' => $cartid]);
     }
     
    /**
     * 删除商品
     */
     public function actionDel()
     {
         $cartid = Yii::$app->request->get('cartid');
         Cart::deleteAll('cartid = :cid',[':cid' => $cartid]);
         return $this->redirect(['cart/index']);
     }
     
}