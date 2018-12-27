<?php
namespace Bpai\Seller\Controllers;
use Phalcon\Tag,
    Bpai\Models\Posts,
    Bpai\Models\Category;
use Qiniu\Auth;
/**
 * @Created by PhpStorm.
 * @Date: 16-6-15
 * @Desc: 文章管理
 */

class PostsController extends ControllerBase
{
    protected $Models;
    protected $Category;
    protected $AK;
    protected $SK;

    public function initialize()
    {
        parent::initialize();
        $this->Models = new Posts();
        $this->Category = new Category();

        $this->Category->setField(array('name'));
        $this->Category->setWhere(array('id'=>$this->get('ctype')));
        $Category = $this->Category->findRec();
        Tag::prependTitle($Category->name);


        $this->Category->setWhere(array('status'=>1));
        $this->Category->setField(array('id','name','type'));
        $this->view->setVar('Category' , $this->Category->listRec());

        if( $this->Controller  == 'posts'){
            $this->view->setVar('ctype',$this->get('ctype'));
        }
    }
}
