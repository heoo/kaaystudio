<?php

namespace Bpai\Web\Controllers;

use Phalcon\Mvc\Controller;
use Bpai\Models\Posts;
use Bpai\Models\Category;
class ControllerAbstract extends Controller
{
    public $_TagConfig;
    public $Language;
    public $Category;

    protected function initialize(){

        $this->view->setVar('ModuleName',$this->router->getModuleName());
        $this->view->setVar('ControllerName',$this->router->getControllerName());
        $this->view->setVar('ActionName',$this->router->getActionName());

        $this->view->setVar('getData',$this->get());

        $config = include_once __DIR__.'/../../../config/tagConfig.php';

 
        if(strpos($_SERVER['HTTP_HOST'],'en') === false){

            $this->_TagConfig = $config['zh'];
            $this->Language = 'zh';
        }else{

            $this->_TagConfig = $config['en'];
            $this->Language = 'en';
        }
        $this->view->setVar('_TagConfig',$this->_TagConfig);
         $this->view->setVar('navigation',$this->getNavigation());
    }

    public function getNavigation(){

        $Navigation = array(
            array('name'=>'Zh','url'=>"http://www.duo-i.com"),
            array('name'=>'En','url'=>"http://en.duo-i.com"),
        );
        $this->Category = new Category();
        $this->Category->setField(array('id','name','type','val'));
        $this->Category->setWhere(array('status'=>1,'isnav'=>1,'language'=>$this->Language));
        $Category = $this->Category->listRec();
        if($Category){
            foreach($Category as $val){
                $url = '';
                if($val['type'] == 'posts'){
                    $url = "http://{$_SERVER['HTTP_HOST']}/{$this->router->getModuleName()}/posts/index?cid={$val['id']}";
                }elseif($val['type'] == 'page') {
                    $url = "http://{$_SERVER['HTTP_HOST']}/{$this->router->getModuleName()}/posts/details?id={$val['val']}";
                }
                $tmp[] = array('name'=>$val['name'],'url'=>$url);
            }

            $Navigation = array_merge($tmp,$Navigation);
        }
        return $Navigation;
    }

    /**
     * 获取GET数据
     * @param null $data
     * @return mixed
     */
    protected function get($name=null, $filters=null, $defaultValue=null){
        return $this->request->get($name, $filters, $defaultValue);
    }

    /**
     * 查看是否是手机访问
     * @return boolean
     */
    protected function isMobile() {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
        {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            }
        }
        return false;
    }

    /**
     * 处理手机访问时需要跳转的目标地址
     * @param string $location 如: /wap/index
     */
    public function mobileRequest($location) {
        if($this->isMobile()) {
            $redirect = "http://".$_SERVER['HTTP_HOST'].$location;

            $this->response->redirect($redirect,true);
        }
    }



    //previous、next
    public function getNearId($id ,$type = 'next'){
        $number = 0;
        $where = array();
        if($id && in_array($type,array('next','previous'))){

            $Models = new Posts();
            $Models->setField(array('id'));
            $where['language'] = $this->Language;
            $where['status'] = 1;
            $where['type'] = 'posts';
            $where['attachment'] = array('attachment','!=','');
            if($type == 'next'){

                $where['id'] = array('id','>',$id);
                $Models->setWhere($where);
                $res = $Models->findRec();
            }else{

                $where['id'] = array('id','<',$id);
                $Models->setWhere($where);
                $Models->setOrder(array('id'=>'DESC'));
                $res = $Models->findRec();
            }

            if($res){

                $number = $res->id;
            }
        }
        return $number;
    }

    public function getPosts($cid =''){

        $arr = array();
        $Models = new Posts();
        $where = array('status'=>1,'language'=>$this->Language,'type'=>'posts','attachment'=>array('attachment','!=',''));
        if($cid){
            $where['cid'] = $cid;
        }
        $Models->setWhere($where);
        $Models->setOrder(array('id'=>'DESC'));
        $Models->setLimit(50);
        $data = $Models->listRec();
        if($data){
            foreach($data as $val){
                if($val->attachment){
                    $len = strpos($val->attachment,',');
                    $thumb = $len ? substr($val->attachment,0,$len) : $val->attachment;
                    $arr[] = array('name'=>$val->name,'thumb'=>$thumb,'id'=>$val->id);
                }
            }
        }
        return $arr;
    }

    protected function getLinks(){
        $result = array();
        $Models = new \Bpai\Models\Links();
        $Models->setWhere(array('status'=>1));
        $Models->setField(array('name','logo','siteurl','type'));
        $Models->setOrder(array('listorder'=>'DESC','id'=>'DESC'));
        $data = $Models->listRec();
        if($data){
            $result = $data->toArray();
        }
        return $result;
    }

}