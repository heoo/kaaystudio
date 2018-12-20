<?php
namespace Bpai\Web\Controllers;
use Phalcon\Tag;
class ControllerBase extends ControllerAbstract
{
    public $Path;
    public $Src;
    protected function initialize()
    {

        parent::initialize();
        $this->view->setTemplateAfter('index');
        Tag::appendTitle('-'.$this->System['web_name']);

    }
}
