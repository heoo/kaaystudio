<?php
namespace Bpai\Seller\Controllers;
use Phalcon\Tag;

/**
 * @Created by PhpStorm.
 * @Desc: 后台控制器基类
 */
class ControllerBase extends ControllerAbstract
{
    protected $Controller;

    protected function initialize()
    {
        parent::initialize();
        Tag::setTitle('-'.$this->tagConfig['adminName']);
        $this->view->setTemplateAfter('main');

        $this->Controller = $this->router->getControllerName();

    }

    /**
     * @desc 列表数据处理
     */
    public function listAction()
    {
        $getData = $this->get();
        $getData['page'] = $getData['page']?:1;
        $getData['rows'] = $getData['rows']?:10;
        $result = array();

        if($getData['name']){
            $where['name'] = array('name','LIKE','%'.$getData['name'].'%');
        }

        if(isset($getData['status'])){
            $where['status'] = $getData['status'];
        }

        if($getData['type']){

            $where['type'] = $getData['type'];
        }

        if($getData['ctype']){

            $ctype = explode('|',$getData['ctype']);
            $getData['module'] = $ctype[1];
            $where['cid'] = $ctype[0];
        }

        if($getData['client']){

            $where['client'] = $getData['client'];
        }

        if($getData['location']){

            $where['location'] = $getData['location'];
        }

        $this->Models->setWhere($where);
        $count =  $this->Models->countRec();

        $this->Models->setLimit($getData['rows'],( $getData['page']-1 )*$getData['rows']);
        $this->Models->setOrder(array('created'=>'DESC'));
        $this->Models->setWhere($where);
        $data =  $this->Models->listRec();
        if($data){

            foreach($data->toArray() as $k=>$v){

                $result['rows'][$k] = $v;
            }
            $result['total'] = $count;
        }
        $this->view->setVar("getData",$getData);
        $this->view->setVar('data',$result);
        $this->view->setVar('pageStr',$this->getNewPages($result['total'],$getData['page'],$getData['rows']));
    }


    /**
     * @desc 添加数据处理
     */
    public function addAction()
    {
        if ($this->isPost() == true){

            $postData = $this->post();
            $postData['code'] = $this->code();
            $postData['key'] = $this->router->getControllerName().'_'.$postData['code'];
            $postData['status'] = 1;
            $postData['created'] = $postData['updated']  = time();
            $postData['createdby'] = $postData['updatedby'] = $this->user['username'];

            $postData['text'] = htmlspecialchars($postData['text']);
            $postData['language'] = $postData['language'] ? $postData['language'] : 'zh';

            if( $this->Controller  == 'posts'){
                $cid = explode('|',$postData['ctype']);
                unset($postData['ctype']);
                $postData['cid'] = $cid[0];
                $postData['type'] = $cid[1];
                $postData['attachment'] = $this->trimString($postData['attachment'],',');
                $postData['thumb'] = self::getThumb($postData['attachment'],$postData['class']);
            }elseif( in_array($this->Controller,array('links','banners')) && $postData['logo']){

                $position = strpos($postData['logo'] , ',');
                if($position){

                    $postData['logo'] = substr($postData['logo'] , 0 , $position);
                }
            } 
            $res = $this->Models->saveRec($postData);
            if($res){

                /* 单页模型 更新分类val 值 */
                if($this->Controller == 'posts' && $cid[1] != 'posts'){

                    $this->Category->setWhere(array('id'=>$postData['cid']));
                    $this->Category->saveRec(array('val'=>$this->Models->id));
                }

                echo json_encode(array('errorNo'=>00000,'errorMsg'=>'添加成功'));exit;
            }else{

                echo json_encode(array('errorNo'=>00001,'errorMsg'=>'添加失败'));exit;
            }
        }
    }

    /**
     * @desc 数据编辑处理
     */
    public function editAction()
    {
        $getData = $this->get();
        $getData['page'] = $getData['page'] ? : 1;
        if ($this->isPost() == true){
            $postData = $this->post();
            $postData['updated'] = time();
            $postData['updatedby'] = $this->user['username'];

            $postData['text'] = htmlspecialchars($postData['text']);

            $postData['language'] = $postData['language'] ? $postData['language'] : 'zh';
            if( $this->Controller  == 'posts'){

                $cid = explode('|',$postData['cid']);
                unset($postData['cid']);
                $postData['cid'] = $cid[0];
                $postData['type'] = $cid[1];

                $postData['attachment'] = $this->trimString($postData['attachment'],',');
                $postData['thumb'] = self::getThumb($postData['attachment'],$postData['class']);
            }elseif( in_array($this->Controller,array('links','banners','system')) && $postData['logo']){

                $position = strpos($postData['logo'] , ',');
                if($position){

                    $postData['logo'] = substr($postData['logo'] , 0 , $position);
                }
            }
            $this->Models->setWhere(array('code'=>$postData['code']));
            $res = $this->Models->saveRec($postData);
            if($res){
                /* 单页模型 更新分类val 值 */
                if($this->Controller == 'posts' && $cid[1] != 'posts'){

                    $this->Category->setWhere(array('id'=>$postData['cid']));
                    $this->Category->saveRec(array('val'=>$postData['id']));
                }

                echo json_encode(array('errorNo'=>00000,'errorMsg'=>'修改成功','page'=>$getData['page']));exit;
            }else{

                echo json_encode(array('errorNo'=>00001,'errorMsg'=>'修改失败','page'=>$getData['page']));exit;
            }
        }else{

            $this->Models->setWhere(array('code'=>$getData['code']));
            $result = $this->Models->findRec();

            if( $this->Controller  == 'posts'){
                $images = explode(',',$result->attachment);
                $this->view->setVar('images',$images);
            }
            $result->text = htmlspecialchars_decode($result->text);
            $this->view->setVar('data',$result);
            $this->view->setVar('page',$getData['page']);
        }
    }


    /**
     * @desc 数据删除处理
     */
    public function deleteAction()
    {
        if($this->isPost()){

            $postData = $this->post();
            $postData['status'] = 0;
            $postData['updated'] = time();
            $postData['updatedby'] = $this->user['username'];
            $this->Models->setWhere(array('code'=>$postData['code']));
            $status = $this->Models->saveRec($postData);

            if($status){

                echo json_encode(array('errorNo'=>00000,'errorMsg'=>'删除成功'));exit;
            }else{

                echo json_encode(array('errorNo'=>00001,'errorMsg'=>'删除失败'));exit;
            }
        }
    }

    private function getThumb($attachment,$class='default'){
        $newname = '';
        $size = [
            'default'=>[700,453],
            'large'=>[1200,777],
            'vertical'=>[700,907],
            'horizontal'=>[1200,389],
        ];
        if($attachment){
//            $this->Imagine = new Imagine();

            $thumb = substr($attachment,0,strpos($attachment,','));
            $thumb = $thumb ? $thumb : $attachment;
            $newname = str_replace('.','thumb.',$thumb);
//            $this->Imagine->open(__DIR__.'/../../../public/'.$thumb)->resize(new Box($size[$class][0], $size[$class][1]))->save(__DIR__.'/../../../public/'.$newname, array('flatten' => false));
        }
        return $newname;
    }


}