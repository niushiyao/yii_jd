<?php
namespace app\controllers;
use yii\web\Controller;
use app\modules\models\Category;
use app\modules\models\Cart;
use app\modules\models\User;
use app\modules\models\Product;
use Yii;
class CommonController extends Controller
{
    public function init()
    {
        $menu = Category::getMenu();
        $this->view->params['menu'] = $menu;
        $data['products'] = [];
        $total = 0;
        if(Yii::$app->session['isLogin'])
        {
            $userid = User::find()->where('username = :username',[':username' => Yii::$app->session['loginname']])->one()->userid;
            if($userid)
            {
                $carts = Cart::find()->where('userid  = :uid',[':uid' => $userid])->asArray()->all();
                foreach($carts as $k=>$pro)
                {
                    $product = Product::find()->where('productid = :pid',[':pid' => $pro['productid']])->one();
                    $data['products'][$k]['cover'] = $product->cover;
                    $data['products'][$k]['title'] = $product->title;
                    $data['products'][$k]['productnum'] = $pro['productnum'];
                    $data['products'][$k]['price'] = $pro['price'];
                    $data['products'][$k]['productid'] = $pro['productid'];
                    $data['products'][$k]['cartid'] = $pro['cartid'];
                    $total += $data['products'][$k]['price'] * $data['products'][$k]['productnum'];
                    
                }
            }
        }
        $data['total'] = $total;
        $this->view->params['cart'] = $data;
    }
    
}