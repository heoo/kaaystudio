<?php
namespace Bpai\Seller\Controllers;

use Phalcon\Exception;
use Phalcon\Mvc\Controller;

class ControllerAbstract extends Controller
{
    protected $user;
    protected $tagConfig;

    protected function initialize(){
        $this->user = $this->session->get('admins');

        if($this->router->getControllerName()=='login' && $this->router->getActionName()!='login' && $this->user==false){
            $this->response->redirect("/{$this->router->getModuleName()}/login/login",true);
        }
        $this->view->setVar('ModuleName',$this->router->getModuleName());
        $this->view->setVar('ControllerName',$this->router->getControllerName());
        $this->view->setVar('ActionName',$this->router->getActionName());

        $this->view->setVar('navigations',$this->navHandle());

        $this->tagConfig = include __DIR__ . "/../../../config/tagConfig.php";
        $this->view->setVar('tagConfig',$this->tagConfig);

    }

    /**
     * @desc 导航权限处理
     */
    public function navHandle()
    {
        $nav = include __DIR__ . "/../config/navConfig.php";
        $divHtml = $liHtml = '';
        $urlArr = array();

        /* 判断当前用户是否为超级管理员 */
        $roleCode = $this->session->get('admins')['role_code'];
        if($roleCode != '7b8312f30b'){
            $aclsNav = $this->session->get('navigations');
        }
        foreach($nav as $navKey=>$navVal){
            $liHtml = '';
//            if(is_array($navVal['element'])){
                foreach($navVal['element'] as $elementKey=>$elementVal){
                    $urlArr = explode('|',$elementVal);
                    $class = '';
                    if($this->router->getControllerName() == $navVal['controller'] && $this->router->getActionName()== $urlArr[2]){
                        $class = ' class="active"';
                    }
                    if($elementKey != 'posts') {
                        if ($roleCode == '7b8312f30b') {
                            $liHtml .= '<li ' . $class . '><a href="' . $urlArr[1] . '"' . $class . ' >' . $urlArr[0] . '</a></li>';
                        } else if (is_array($aclsNav) && in_array($elementKey, $aclsNav)) {
                            $liHtml .= '<li ' . $class . '><a href="' . $urlArr[1] . ' ">' . $urlArr[0] . '</a></li>';
                        }
                    }
                    if($elementKey == 'posts'){
                        $liHtml .= self::getCoreMenus();
                    }
                }
//            }
            if($liHtml)
            {
                $class = $open = '';
                $res = in_array($this->router->getControllerName(),array_keys ($navVal['element']));
                if($this->router->getControllerName() == 'data' && $this->router->getActionName() == 'list'){
                    //$res = 1;
                }
                if($res){
                    $open = 'open';
                    $display = 'style="display: block;"';
                } else{
                    $display = 'style="display: none;"';
                }

                if($this->router->getControllerName() == $navVal['controller'] )
                {
                    $class = ' class="active"';
                }
                $divHtml .= ' <li '.$class.' data-key="'.$navVal['controller'].'-'.$this->router->getControllerName();
                $divHtml .= '" class="'.$open.'" ><a href="javascript:;">
                              <i class="icon-sitemap"></i>
                              <span class="title">'.$navVal['name'].'</span>
                              <span class="arrow '.$open.'"></span>
                              </a> <ul class="sub-menu" '.$display.'>'.$liHtml.'</ul></li>';
            }
        }

        return $divHtml;
    }

    protected function post($name=null, $filters=null, $defaultValue=null){
        return $this->request->getPost($name, $filters, $defaultValue);
    }

    protected function get($name=null, $filters=null, $defaultValue=null){
        return $this->request->get($name, $filters, $defaultValue);
    }

    protected function code(){
        return substr(md5(microtime().rand('10000','99999')),0,10);
    }

    protected function trimspace($data){
        if($data){
            foreach($data as $key=>$val){
                if(!is_array($val)){
                    $newData[$key] = trim($val);
                }else{
                    $newData[$key] = $this->trimspace($val);
                }
            }
            return $newData;
        }else{
            return false;
        }
    }

    function trimString( $String , $key){
        $tmp = '';
        if($String){
            $arr = explode($key,$String);
            foreach($arr as $val){
                if($val){
                    $tmp .= $val . $key;
                }
            }
            $tmp = rtrim($tmp,$key);
            unset($String);
            $String = $tmp;
        }
        return $String;
    }

    public function getCoreMenus()
    {
        $string = '';
        $Obj = new \Bpai\Models\Category();
        $Obj->setField(array('name','id','type'));
        $Obj->setOrder(array('id'=>'DESC'));
        $res = $Obj->listRec();
        if($res){
            foreach($res as $val){
                $string .= "<li><a href='/{$this->router->getModuleName()}/posts/list?ctype={$val->id}|{$val->type}'>{$val->name}</a></li>";
            }
        }
        return $string;
    }

    function getNewPages($total,$page=1,$psize=10)
    {
        $myPage = new \Bpai\Plugins\numPage($total,$page,$psize);
        return $myPage->GetPager();
    }

    protected function isPost(){
        return $this->request->isPost();
    }

    protected function isGet(){
        return $this->request->isGet();
    } 
}
