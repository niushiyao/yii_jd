<?php
namespace app\modules\models;
use yii\db\ActiveRecord;

class Admin extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%admin}}";
    }
}