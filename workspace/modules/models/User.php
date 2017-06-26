<?php 
namespace app\modules\models;
use yii\db\ActiveRecord;
use Yii;

class User extends ActiveRecord
{
    public $repass;
    public static function tableName()
    {
        return "{{%user}}";
    }
    
    /**
     * 规则
     */
     public function rules()
     {
         return [
            ['username','required','message' => '用户名不能为空', 'on'=>['reg']],
            ['userpass','required','message' => '密码不能为空','on'=>['reg']],
            ['useremail','required','message' => '邮箱不能为空','on'=>['reg']],
            ['useremail','email','message' => '邮箱格式不正确','on'=>['reg']],
            ['useremail','unique','message' => '此邮箱已被注册','on'=>['reg']],
            ['repass','required','message' => '确认密码不能为空','on'=>['reg']],
            
        ];
     }
    
    /**
     * 添加用户
     */
     public function reg($data, $scenario = 'reg')
     {
         $this->scenario = $scenario;
         if($this->load($data) && $this->validate())
         {
             $this->createtime = time();
             $this->userpass = md5($this->userpass);
             if($this->save(false))
             {
                 return true;
             }
         }
         return false;
     }
     
     /**
      * 获得某个用户的扩展信息
      */
      public function getProfile()
      {
          return $this->hasOne(Profile::className(), ['userid' => 'userid']);
      }
}