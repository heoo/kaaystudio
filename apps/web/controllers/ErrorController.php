<?php
namespace Bpai\Web\Controllers;
use Phalcon\Tag;

/**
 * @desc 异常处理 控制器
 */
class ErrorController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        Tag::prependTitle($this->_TagConfig[$this->router->getControllerName()]);
    }
    /**
     * @desc 测试接口
     */
    public function indexAction()
    {
    }

}