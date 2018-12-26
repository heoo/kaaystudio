<?php
/**
 * Created by JetBrains PhpStorm.
 * User: haowei
 * Date: 14-7-11
 * Time: 上午8:15
 * To change this template use File | Settings | File Templates.
 */

namespace Catchtech\Extensions\Model;

class BaseModel extends \Catchtech\Extensions\Model\ModelAbstract {

  // use \Catchtech\Extensions\Traits\MagicGeneral;

    public function initialize()
    {
        $this->setConnectionService('db');
    }

    public $property = array();
    public $bind = array();
    /**
     * 处理where 数组 产生where语句
     * @$where  要处理的where数组
     */
    public function setWhere($where)
    {
        if(is_array($where) === true)
        {//是数组

            $str = '';
            foreach ($where as $k=>$v)
            {
                if(is_array($v)==true)
                {	//如果是二位数组
                    $str .= " $v[0] ".' '.$v[1]." :$v[0]: AND ";
                    $this->bind[$v[0]]=$v[2];
                }
                else
                {
                    $str .= "{$k}=:".$k.":".' AND ';
                    $this->bind[$k]=$v;
                }
            }
            $this->property['conditions'] .=" AND ".substr($str, 0,-4);
        }else{
            $this->property['conditions'] .= $where;
        }
    }

    /**
     * @此函数的作用是查询多少条记录
     * @$vs_row，limit查询多少条
     * @vs_start,limit 从那开始
     * @返回值是LIMIT
     */
    public function setLimit($vs_row=0,$vs_start=0)
    {
        if($vs_row !=0 && !empty($vs_row))
        {
            if(!empty($vs_start))
            {
               $this->property['limit'] = array('number' => $vs_row, 'offset' => $vs_start);
            }
            else
            {
                $this->property['limit'] = $vs_row;
            }
        }

    }

    /**
     * @此函数的作用查询的字段值
     * @$vr_name,要查询的字段名，array，string
     * @返回值是所有要查询的字段名
     */
    public function setField($vr_name)
    {
        if(is_array($vr_name)==true)
        {
            $fileds = implode(',',$vr_name);
            $this->property['columns'] = $fileds;
        }else{
            $this->property['column'] = $vr_name;
        }
    }

    /**
     * @此函数的作用是setIn
     * @$vs_field,in前面的字段
     * @$vs_price，为数组传多个值
     * @返回值是 WHERE 字段IN 值 AND 字段 操作符 值
     */
    public function setIn($vs_field,array $vs_price)
    {
        if(is_array($vs_price) && $vs_field)
        {
            $set_inValue = implode(',',$vs_price);
            $this->property['conditions'] .= ' and '."{$vs_field} IN ({$set_inValue})";
        }
    }
    
    /**
     * @此函数的作用是setNotIn
     * @$vs_field,in前面的字段
     * @$vs_price，为数组传多个值
     * @返回值是 WHERE 字段IN 值 AND 字段 操作符 值
     */
    public function setNotIn($vs_field,array $vs_price)
    {
    	if(is_array($vs_price) && $vs_field)
    	{
    		$set_inValue = implode(',',$vs_price);
    		$this->property['conditions'] = ' and '."{$vs_field} NOT IN ({$set_inValue})";
    	}
    }

    /**
     * @setOrIn此函数的作用是 或者in
     * @$vs_field,in前面的字段
     * @$vs_price，为数组传多个值
     * @返回值是 WHERE 字段IN 值 AND 字段 操作符 值
     */
    public function setOrIn($vs_field,array $vs_price)
    {
        if(is_array($vs_price) && $vs_field && isset($this->property['conditions']) && !empty($this->property['conditions']))
        {
            $set_inValue = implode(',',$vs_field);
            $this->property['conditions'] = ' or '."{$vs_field} IN ({$set_inValue}) ";
        }
        else
        {
            throw new \Phalcon\Exception('查询条件语法错误，请重试');
        }
    }
    /**
     * @setOrder此函数的作用是排序
     * @array $operateOrders 字段名  可以是数组也可以传一个单独的字符串！array（id，title）
     * @返回值是排序
     */
    public function setOrder($orders)
    {
        if($orders && is_array($orders))
        {
            $str ='';
            foreach ($orders as $k=>$v)
            {
                $str .= "{$k}  ".$v.', ';
            }
            $this->property['order'] = substr($str, 0,-2);
        }
    }



    /**
     * @setGroup此函数的作用是分组
     * @array $field 字段名  可以是数组也可以传一个单独的字符串！array（id，title） 或 id 或 id,title
     * @返回值是对数据列进行归组
     */
    public function setGroup($field)
    {
        if($field)
        {
            if(is_array($field))
            {
                $this->property['group'] = implode(',',$field);
            }
            else
            {
                $this->property['group'] = $field;
            }
        }
    }

    /**
     * @getWhere 获取Where条件
     *
     */
    public function getWhere()
    {
    	
        $this->property['conditions'] = substr($this->property['conditions'],4);
        //var_dump( $this->property['conditions'] );exit;
        $this->property['bind'] = $this->bind;
        if($this->property['conditions']==' ') {
        	$this->clear();
        }
    }

    /**
     * 清楚条件属性
     */
    public function clear()
    {
        $this->property = array();
        $this->bind = array();
    }

    /**
     * 获取列表
     * 直接调取获取全部条数！在调取基础此函数之前调取 setwhere setorder 对应的为此函数增加条件！
     */
    public function listRec()
    {
    	
         $this->getWhere(); //获取查询条件
//        echo '<pre>';var_dump($this->property);exit;
        $res = $this->find($this->property);
        //var_dump($res);exit;
        $this->clear();
        return $res;
    }

    /**
     * 求和
     * 直接调取获取全部条数！在调取基础此函数之前调取 setwhere setorder 对应的为此函数增加条件！
     */
    public function sumRec()
    {
        $this->getWhere();
//        var_dump($this->property);exit;
        $res = $this->sum($this->property);
//        var_dump($res);exit;
        $res = $res ? $res : 0;
        $this->clear();
        return $res;
    }

    /**
     * 计算条数
     * 直接调取获取全部条数！在调取基础此函数之前调取 setwhere setorder 对应的为此函数增加条件！
     */
    public function countRec()
    {
    	
        $this->getWhere();
//        var_dump($this->property);exit;
        $res = $this->count($this->property);
        
        $this->clear();
        
        return $res;
    }

    /**
     * @desc 计算平均值
     * 直接调取获取全部条数！在调取基础此函数之前调取 setwhere setorder 对应的为此函数增加条件！
     */
    public function avgRec()
    {
        $this->getWhere();
        $res = $this->average($this->property);
        $this->clear();
        return $res;
    }

    /**
     * @desc 获取最大值
     * */
    public function maxRec()
    {
        $this->getWhere();
        $res = $this->maximum($this->property);
        $this->clear();
        return $res;
    }


    /**
     * @desc 获取最小值
     * */
    public function minRec()
    {
        $this->getWhere();
        $res = $this->minimum($this->property);
        $this->clear();
        return $res;
    }


    /**
     * @setOrWhere此函数的作用是  实现 或 条件语句
     * @$vs_field,in前面的字段
     * @$vs_value，条件等于的值
     * @返回值是 WHERE 字段where 值 or 字段 操作符 值
     */
    public function setOrWhere($vs_field,$vs_value)
    {
        if(is_array($vs_value) && $vs_field && isser($this->property['conditions']) && !empty($this->property['conditions']))
        {
            $set_inValue = implode(',',$vs_field);
            $this->property['conditions'] = ' or '.$vs_field.'= "'.$set_inValue.'"';
        }
        else
        {
            throw new \Phalcon\Exception('查询条件语法错误，请重试');
        }
    }

    /**
     * 获取一条数据
     * 直接调取获取全部条数！在调取基础此函数之前调取 setwhere setorder 对应的为此函数增加条件！
     */
    public function findRec()
    {
        $this->getWhere();
        $res = $this->findFirst($this->property);
        $this->clear();
        return $res;
    }

    /**
     * 处理数据
     * $data 要处理的数据
     * 直接调取获取全部条数！在调取基础此函数之前调取 setwhere  对应的为此函数增加条件！
     */
    public function saveRec($data,$dataKey="")
    {
    	 
        if($this->property['conditions'])
        {
            $this->getWhere();
            $info =  $this->findFirst($this->property);
            $this->clear();

            if($info)
            {
                return $info->save($data,$dataKey);
            }
            return false;
        }
        else
        {
            return $this->save($data,$dataKey);
        }

    }
    

    /**
     * 删除数据（1次只能删除1条）
     * 直接调取获取全部条数！在调取基础此函数之前调取 setwhere setorder 对应的为此函数增加条件！
     * @example
     * $model=new model();
     * $model->setWhere(array('code'=>$code));
     * $model->delRec();
     */
    public function delRec()
    {
        $this->getWhere();
        $info =  $this->find($this->property);
        $this->clear();
        if($info)
        {
            return $info->delete();
        }
        return false;
    }
	
    public function execute($sql) {
    	
    	$query =$this->modelsManager->createQuery($sql);
    	
    	$result = $query->execute();
    	return $result;
    }
    
    public function setBetween($field, $min=null, $max=null) {
     	if($min) {
     		$this->property['conditions'] .= ' and '.$field.' >='.$min;
     	}
     	if($max) {
     		$this->property['conditions'] .= ' and '.$field.' <='.$max;
     	}
    }

}