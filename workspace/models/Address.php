<?php
namespace app\models;
use yii\db\ActiveRecord;

class Address extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%address}}";
    }
}