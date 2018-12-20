<?php
namespace Bpai\Web\Controllers;
use Phalcon\Tag;
class ControllerBase extends ControllerAbstract
{
    public $Path;
    public $Src;
    protected function initialize()
    {
        Tag::setTitle($this->System['web_name']);
        parent::initialize();
        $this->view->setTemplateAfter('index');
    }
}
