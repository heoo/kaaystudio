<?php
namespace Bpai\Web\Controllers;
use Phalcon\Tag;
use Bpai\Models\Posts;
use PHPMailer\PHPMailer\PHPMailer;

class DetailsController extends ControllerBase {

    public $Models;
	public function initialize() {
		parent::initialize();
		$this->Models = new Posts();
	}
	
	public function indexAction() {
        $this->Models->setWhere(array('id'=>$this->get('id')));
        $result = $this->Models->findRec();
        if($result){
            $data = $result->toArray();
        }

        if($this->checkLanguage()){
            $data['name'] = $data['en_name'];
            $data['text'] = $data['en_text'];
            $data['digest'] = $data['en_digest'];
        }
        Tag::setTitle($data['name']);

        if($data['cid']){
            $category = self::getCategory($data['cid']);
            if($category){
                $this->view->setVar('category',$category);
                Tag::setTitle($data['name'].'-'.$category['name']);
            }
        }
        $data['text'] = htmlspecialchars_decode($data['text']);
        $data['time'] = date('Y-m-y H:i',$data['created']);

        self::setPostsHits($data['id']);
        $this->view->setVar('data',$data);

        $this->Models->setField(array('id','name'));
        $this->Models->setWhere(array('status'=>1,'cid'=>$data['cid'],array('id','<',$data['id'])));
        $prevRes = $this->Models->findRec();
        $this->view->setVar('prev',$prevRes);

        $this->Models->setField(array('id','name'));
        $this->Models->setWhere(array('status'=>1,'cid'=>$data['cid'],array('id','>',$data['id'])));
        $nextRes = $this->Models->findRec();
        $this->view->setVar('next',$nextRes);

        if( $data['id']== '21'){
            $this->view->pick('details/contact');
        }
	}

	public function sendAction()
    {
        if($this->post()){
            $postData = $this->post();

            $name = trim($postData['name']);
            $email = trim($postData['email']);
            $contents = trim($postData['messages']);

            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = 'smtp.qq.com';
            $mail->SMTPAuth = true;
            $mail->Username = '';
            $mail->Password = '';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->CharSet = 'UTF-8';
            $mail->From = $this->System['web_name'];
            $mail->FromName = $this->System['web_name'];
            $mail->setFrom($mail->Username);
            $mail->addAddress($postData['email']);
            $messages = "\n
                        Name:{$name} \n
                        Email:{$email} \n
                        Contents:{$contents}
            ";
            $mail->Subject = $name; //邮件的主题
            $mail->Body    = $messages;
            if(!$mail->send()) {
                return $mail->ErrorInfo;
            } else {
                return '';
            }
        }else{
            $this->response->redirect("/",true);
        }
    }

}
