<?php 
namespace app\modules\models;
use yii\db\ActiveRecord;

class OrderDetail extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%order_detail}}";
    }
    
    public function rules()
    {
        return [
            [['productid','productnum','price','orderid','createtime'], 'required'],
        ];
    }
    
    /**
     * 增加数据
     */
    public function add($data)
    {
        if($this->load($data) && $this->save())
        {
            return true;
        }
        return false;
    }

}