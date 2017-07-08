<?php
/**
*商品相关页面
* @Author niushiyao
* @Date   2017-06-21
*/
namespace app\controllers;
use yii\web\Controller;
use app\controllers\CommonController;
use app\modules\models\Product;
use Yii;
use yii\data\Pagination;

class ProductController extends CommonController
{
    //public $layout = false;
    /**
     * 商品列表页面
     * @Author niushiyao
     * @Date   2017-06-21
     */
    public function actionIndex()
    {
        $this->layout = 'layout2';
        $cid = Yii::$app->request->get('cateid');
        $where = "cateid = :cid and ison = '1'";
        $params = [':cid' => $cid];
        $model = Product::find()->where($where, $params);
        $all = $model->asArray()->all();
        $count = $model->count();
        $pageSize = Yii::$app->params['pageSize']['frontproduct'];
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        $all = $model->offset($pager->offset)->limit($pager->limit)->asArray()->all();
        $tui = $model->where($where . ' and istui = "1"', $params)->orderby("createtime desc")->limit(5)->asArray()->all();
        $hot = $model->where($where . ' and ishot = "1"', $params)->orderby('createtime desc')->limit(5)->asArray()->all();
        $sale = $model->where($where . ' and issale = "1"', $params)->orderby('createtime desc')->limit(5)->asArray()->all();
        return $this->render('index', ['sale' => $sale, 'tui' => $tui, 'hot' => $hot, 'all' => $all, 'pager' => $pager, 'count' => $count]);
    }
    
    /**
     * 商品详情页面
     * @Author niushiyao
     * @Date   2017-06-21
     */
     public function actionDetail()
     {
         $this->layout = 'layout2';
         $productid = Yii::$app->request->get('productid');
         $product = Product::find()->where('productid = :id',[':id' => $productid])->asArray()->one();
         return $this->render('detail', ['product' => $product]);
     }
    
    
}