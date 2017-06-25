<?php
namespace app\modules\models;
use yii\db\ActiveRecord;
use Yii;

class Admin extends ActiveRecord
{
    public $rememberMe = true;
    public $repass;
    public static function tableName()
    {
        return "{{%admin}}";
    }
    
    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            ['adminuser', 'required', 'message' => '管理员帐号不能为空', 'on' => ['login','seekpass','changepass']],
            ['adminpass', 'required', 'message' => '管理员密码不能为空', 'on' => ['login','changepass']],
            ['rememberMe','boolean', 'on' => 'login'],
            ['adminpass', 'validatePass', 'on' => 'login'],
            ['adminemail', 'required', 'message' => '电子邮箱不能为空', 'on' => 'seekpass'],
            ['adminemail', 'email', 'message' => '邮箱格式不正确', 'on' => 'seekpass'],
            ['adminemail', 'validateEmail','on' => 'seekpass'],
            ['repass','required','message' => '确认密码不能为空','on'=>'changepass'],
            ['repass','compare','compareAttribute' => 'adminpass','message' => '两次密码输入不一致','on' => 'changepass'],
        ];
    }
    
    /**
     * 验证密码的方法
     */
    public function validatePass()
    {
        if(!$this->hasErrors())
        {
            $data = self::find()->where('adminuser = :user and adminpass = :pass',[":user" => $this->adminuser,":pass" => md5($this->adminpass)])->one();
            if(is_null($data))
            {
                $this->addError('adminpass','用户名或者密码错误');
            }
        }
    }
    
    /**
     * 验证邮箱是否为用户的注册邮箱
     * @author niushiyao
     * @date   2017-06-25
     */
     public function validateEmail()
     {
         if(!$this->hasErrors())
         {
            $userInfo = self::find()->where('adminuser = :user and adminemail = :email',[':user' => $this->adminuser,':email' => $this->adminemail])->one(); 
            if(is_null($userInfo))
            {
                $this->addError("adminemail","管理员帐号和邮箱不匹配");
            }
         }
     }
    
    /**
     * 登录操作
     */
    public function login($data)
    {
        $this->scenario = 'login';
        if($this->load($data) && $this->validate())
        {
            //保存用户登录信息到seesion
            $lifetime = $this->rememberMe ? 24*7*3600 : 0;
            $session = Yii::$app->session;
            session_set_cookie_params($lifetime);
            $session['admin'] = [
                'adminuser' => $this->adminuser,
                'isLogin' => 1,
            ];
            $this->updateAll(['logintime' => time(),'loginip' => ip2long(Yii::$app->request->userIp)],'adminuser = :user',[':user' => $this->adminuser]);
            return (bool)$session['admin']['isLogin'];
        }
        return false;
    }
    
    /**
     * 找回密码方法
     * @author niushiyao
     * @date   2017-06-25
     */
     public function seekPass($data)
     {
         $this->scenario = 'seekpass';
         if($this->load($data) && $this->validate())
         {
             //发送邮件
             $time = time();
             $token = $this->createToken($data['Admin']['adminuser'],$time);
             $mailer = Yii::$app->mailer->compose('seekpass',['adminuser' => $data['Admin']['adminuser'],'time' => $time,'token' => $token]);
             $mailer->setFrom("imooc_shop@163.com");
             $mailer->setTo($data['Admin']['adminemail']);
             $mailer->setSubject("慕课商城-找回密码");
             if($mailer->send())
             {
                 return true;
             }
         }
         return false;
     }
     
     /**
      * 生成token
      */
      public function createToken($adminuser, $time)
      {
          return md5(md5($adminuser).base64_encode(Yii::$app->request->userIp).md5($time));
      }
      
      /**
       * 修改密码
       */
       public function changePass($data)
       {
           $this->scenario = 'changepass';
           if($this->load($data) && $this->validate())
           {
               return (bool)$this->updateAll(['adminpass' => md5($this->adminpass)],'adminuser = :user',[':user' => $this->adminuser] );
           }
           return false;
       }
    
}