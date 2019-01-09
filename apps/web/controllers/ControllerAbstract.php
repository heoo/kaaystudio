<?php

namespace Bpai\Web\Controllers;

use Phalcon\Mvc\Controller;
use Bpai\Models\Posts,
    Bpai\Models\Category,
    Bpai\Models\System,
    Bpai\Models\Banners;

class ControllerAbstract extends Controller
{
    public $_TagConfig;
    public $Language;
    public $Category;
    public $System;
    public $_Language;

    protected function initialize(){

        $this->view->setVar('ModuleName',$this->router->getModuleName());
        $this->view->setVar('ControllerName',$this->router->getControllerName());
        $this->view->setVar('ActionName',$this->router->getActionName());
        $this->view->setVar('getData',$this->get());
        $this->_TagConfig = include_once __DIR__.'/../../../config/tagConfig.php';
        $this->view->setVar('_TagConfig',$this->_TagConfig);
        $this->view->setVar('navigation',$this->getNavigation());

        if($this->session->get('system') == false){
            self::getSystem();
        }
        $this->System = $this->session->get('system');
        $this->view->setVar('system',$this->System);
        $this->view->setVar('links',self::getLinks());
        $this->view->setVar('banners',self::getBanners());

        $this->view->setVar('url','http://'.$this->request->getHttpHost().$this->request->getURI());
        $this->_Language = self::checkLanguage();
    }

    public function getNavigation(){

        $this->Category = new Category();
        $this->Category->setField(array('id','name','en_name','type','val','text','en_text','more'));
        $this->Category->setWhere(array('status'=>1,'isnav'=>1));
        $this->Category->setOrder(array('listorder'=>'DESC'));
        $this->Category->setLimit(9);
        $Category = $this->Category->listRec();
        if($Category){
            foreach($Category as $val){
                $url = '';
                if( in_array($val->type,array('posts','images'))){
                    $url = "http://{$this->request->getHttpHost()}/{$this->router->getModuleName()}/posts/index?cid={$val->id}";
                }elseif($val->type == 'page') {
                    $url = "http://{$this->request->getHttpHost()}/{$this->router->getModuleName()}/details/index?id={$val->val}";
                }elseif($val->type == 'url'){
                    $url = $val->val;
                }
                if($this->_Language)
                    $val->name = $val->en_name;
                if($val->name)
                    $Navigation[] = array('name'=>$val->name,'url'=>$url);
            }
        }
        return $Navigation;
    }

    protected function getSystem(){
        $Models = new System();
        $this->session->set('system',$Models->findRec()->toArray());
    }

    /**
     * 获取GET数据
     * @param null $data
     * @return mixed
     */
    protected function get($name=null, $filters=null, $defaultValue=null){
        return $this->request->get($name, $filters, $defaultValue);
    }


    protected function post($name=null, $filters=null, $defaultValue=null){
        return $this->request->getPost($name, $filters, $defaultValue);
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
//            $where['language'] = $this->Language;
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

    public function getPosts($cid=0,$rows=0,$start=0){

        $arr = array();
        $Models = new Posts();
        $where = array('status'=>1);
        if($cid){
            $where['cid'] = $cid;
        }
        $rows = $rows ? $rows : 10;
        $Models->setWhere($where);
        $Models->setOrder(array('id'=>'ASC'));
        $Models->setLimit($rows,$start);
        $data = $Models->listRec();
        if($data){
            $arr = $data->toArray();
        }
        return $arr;
    }

    public function getPostsCount($cid =''){

        $count = 0;
        $Models = new Posts();
        $where = array('status'=>1,'type'=>'posts');
        if($cid){
            $where['cid'] = $cid;
        }
        $Models->setWhere($where);
        $count = $Models->countRec();
        return $count;
    }

    public function setPostsHits($id =''){
        if($id){
            $Models = new Posts();
            $where = array('id'=>$id);
            $Models->setWhere($where);
            $result = $Models->findRec();
            if($result){
                $result->saveRec(array('hits'=>$result->hits+1));
            }
        }
    }
    protected function getLinks(){
        $result = array();
        $language = $this->_Language ? 'en' : 'zh';
        $Models = new \Bpai\Models\Links();
        $Models->setWhere(array('status'=>1,'language'=>$language));
        $Models->setField(array('name','logo','siteurl','type'));
        $Models->setOrder(array('listorder'=>'DESC','id'=>'DESC'));
        return $Models->listRec()->toArray();
    }

    protected function getBanners(){
        $banners = array();
        $Models = new Banners();
        $location = $this->router->getControllerName();
        $location = $location ? $location : 'index';
        $Models->setField(array('id','code','siteurl','name','logo'));
        $Models->setWhere(array('location'=>$location,'status'=>1));
        $Models->setOrder(array('listorder'=>'DESC'));
        $data = $Models->listRec();
        if($data){
            $banners = $data->toArray();
        }
        return $banners;
    }
    protected function getCategory($cid){
        $data = array();
        if($cid){
            $this->Category = new Category();
            $this->Category->setWhere(array('id'=>$cid));
            $this->Category->setField(array('id','name','type'));
            $result = $this->Category->findRec();
            if($result){
                $data = $result->toArray();
            }
        }
        return $data;
    }

    protected function checkLanguage(){
        $language = false;
        if(strpos($this->request->getHttpHost(),'en') !== false){
            $language = true;
        }
        return $language;
    }
}