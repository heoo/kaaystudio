<?php

namespace Bpai\Seller\Controllers;
use Qiniu\Storage\UploadManager;
/**
 * @desc 文件上传处理
 * */
class UploadsController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        \Phalcon\Tag::setTitle('文件上传处理');
        $this->uploads = new \Bpai\Plugins\fileUploadPlugin();

    }
    
    public function indexAction()
    {
        echo 'api Uploads!';exit;
    }

    /**
    * @desc 文件上传接口
    * @author 王鹏剑
    * */
    public function ImagesAction()
    {
        //关闭缓存
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $res = $this->uploads->upload('file', $_POST);
        $isFlash = false;
        if(strpos($res,'flv') || strpos($res,'mp4')){
            $isFlash = true;
        }
        if($this->QNToken){
            // 构建 UploadManager 对象
            $uploadMgr = new UploadManager();

            $fileName = $this->uploads->getFileName();
            $tmpName = 'http://'.$_SERVER['SERVER_NAME'].$res;

            list($Qret, $error) = $uploadMgr->put($this->QNToken,$fileName,file_get_contents($tmpName));
            if(!$error){
                unlink(__DIR__.'/../../../public'.$res);
                $res = 'http://pjvj8fgws.bkt.clouddn.com/'.$fileName;
            }
        }
        die('{"status":0,"Succenss":true, "path": "'.$res.'","isFlash":"'.$isFlash.'"}');
    }
}