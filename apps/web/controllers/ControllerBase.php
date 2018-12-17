<?php
namespace Bpai\Web\Controllers;
use Phalcon\Tag;
class ControllerBase extends ControllerAbstract
{
    public $Path;
    public $Src;
    protected function initialize()
    {
        Tag::setTitle('-Duo-i');
        parent::initialize();
        $this->view->setTemplateAfter('index');

        $this->view->setVar('links',$this->getLinks());


        $this->Path =  __DIR__.'/../../../public/logo.txt';
        
        if(file_exists($this->Path)){

            $this->Src = file_get_contents($this->Path);
        }

        if(!$this->Src){

            $this->Src = '/templates/duo-i/images/logo.png';
        }

        $this->view->setVar('src',$this->Src);
    }
    
}
