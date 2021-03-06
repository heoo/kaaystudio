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
            if($postData['en_text'])
                $postData['en_text'] = htmlspecialchars($postData['en_text']);


            if( $this->Controller  == 'posts'){
                $cid = explode('|',$postData['ctype']);
                unset($postData['ctype']);
                $postData['cid'] = $cid[0];
                $postData['type'] = $cid[1];
                $postData['attachment'] = self::getThumb($postData['thumb'],$postData['class']);
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
        $type = '';
        $typeArr = explode('|',$this->request->getURI());
        if($typeArr){
            $type = $typeArr[1];
        }
        $this->view->setVar('type',$type);
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
            if($postData['en_text'])
                $postData['en_text'] = htmlspecialchars($postData['en_text']);

            if( $this->Controller  == 'posts'){

                $cid = explode('|',$postData['cid']);
                unset($postData['cid']);
                $postData['cid'] = $cid[0];
                $postData['type'] = $cid[1];
                $postData['attachment'] = self::getThumb($postData['thumb'],$postData['class']);
            }
//            echo '<pre>';
//            var_dump($postData);exit;
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
            $result->en_text = htmlspecialchars_decode($result->en_text);
            $this->view->setVar('data',$result);
            $this->view->setVar('page',$getData['page']);

            $type = '';
            $typeArr = explode('|',$this->request->getURI());
            if($typeArr){
                $type = $typeArr[1];
            }
            $this->view->setVar('type',$type);
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

    private function getThumb($thumb,$class='default'){
        $attachment = '';
        $size = [
            'default'=>[600,300],
            'wide'=>[1250,710],
            'portrait'=>[600,710]
        ];
        if($thumb){
            if($this->QNToken){
                $attachment = $thumb."?imageView2/1/w/{$size[$class][0]}/h/{$size[$class][1]}/q/100";
            }else{

                $path = __DIR__."/../../../public/";
                $sourceFile = $path.$thumb;
                $attachment = str_replace('.',"-{$size[$class][0]}X{$size[$class][1]}.",$thumb);
                $image = new \Phalcon\Image\Adapter\GD($sourceFile);

                $width = $image->getWidth();
                $height = $image->getHeight();

                $offsetX = $offsetY = 0;
                $offsetX = ($width - $size[$class][0]) / 2;
                switch ($class){
                    case 'wide' :
                        $image->crop($size[$class][0],$height,$offsetX);
                        break;
                    default :
                        $offsetY = ($height - $size[$class][1]) / 2;
                        $image->crop($size[$class][0],$size[$class][1],$offsetX,$offsetY);
                }
                $image->save($path.$attachment);
            }
        }
        return $attachment;
    }


}