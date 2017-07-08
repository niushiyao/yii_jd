<?php
namespace app\modules\models;
use yii\db\ActiveRecord;
use Yii;
class Cart extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%cart}}";
    }
    
    /**
     * 制定规则
     */
     public function rules()
     {
        return [
            [['productid','productnum','userid','price'], 'required'],
            ['createtime', 'safe'],
        ];
     }
}