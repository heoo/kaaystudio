<?php
namespace Bpai\Seller\Controllers;
use Phalcon\Tag;

class LogoController extends ControllerBase
{
    public $Path;
    public $Src;
    public function initialize()
    {
        parent::initialize();
        Tag::prependTitle('Logo管理');
        $this->Path =  __DIR__.'/../../../public/logo.txt';

        if(file_exists($this->Path)){

            $this->Src = file_get_contents($this->Path);
        }

        if(!$this->Src){

            $this->Src = '/templates/duo-i/images/logo.png';
        }

        $this->view->setVar('src',$this->Src);
    }

    public function listAction(){
    }

    public function editAction(){

        if($this->post('logo')){
            $logo = $this->trimString($this->post('logo'),',');
            if ($fp = fopen($this->Path, "w")) {
                 @fwrite($fp, $logo);
                 fclose($fp);
            }
            echo json_encode(array('errorNo'=>00000,'errorMsg'=>'success'));exit;
        }
    }

}

