<?php 
namespace app\modules\models;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use Yii;

class Category extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%category}}";
    }
    
    /**
     * 给英文赋值中文名称
     * @date  2017-07-01
     */
    public function attributeLabels()
    {
        return [
            'parentid' => '上级分类',
            'title' => '分类名称',
        ];
    }
    
    /**
     * 验证方法
     */
     public function rules()
     {
         return [
            ['parentid', 'required', 'message' => '上级分类不能为空'],
            ['title', 'required', 'message' => '标题名称不能为空'],
            ['createtime', 'safe'],
         ];
     }
    
    /**
     * 添加方法
     */
     public function add($data)
     {
         $data['Category']['createtime'] = time();
         if($this->load($data) && $this->save())
         {
             return true;
         }
         return false;
     }
     
     /**
      * 获得分类数据
      */
      public function getData()
      {
          $cates = self::find()->all();
          //将对象转为数组
          $cates = ArrayHelper::toArray($cates);
          return $cates;
      }
      
      /**
       * 获得树
       */
     public function getTree($cates, $pid = 0)
     {
        $tree = [];
        foreach($cates as $cate)
        {
            if($cate['parentid'] == $pid)
            {
                $tree[] = $cate;
                $tree = array_merge($tree, $this->getTree($cates,$cate['cateid']));
            }
        }
        return $tree;
     }
     
     /**
      * 添加前缀
      */
     public function setPrefix($data, $p = "|-----")
    {
        $tree = [];
        $num = 1;
        $prefix = [0 => 1];
        while($val = current($data)) {
            $key = key($data);
            if ($key > 0) {
                if ($data[$key - 1]['parentid'] != $val['parentid']) {
                    $num ++;
                }
            }
            if (array_key_exists($val['parentid'], $prefix)) {
                $num = $prefix[$val['parentid']];
            }
            $val['title'] = str_repeat($p, $num).$val['title'];
            $prefix[$val['parentid']] = $num;
            $tree[] = $val;
            next($data);
        }
        return $tree;
    }
    
    /**
     * 获得选项
     */
    public function getOptions()
    {
        $data = $this->getData();
        $tree = $this->getTree($data);
        $tree = $this->setPrefix($tree);
        $options = ['添加顶级分类'];
        foreach($tree as $cate) {
            $options[$cate['cateid']] = $cate['title'];
        }
        return $options;
    }
     

}