<?php
namespace Bpai\Web\Controllers;
class DemoController extends ControllerBase {

	public function initialize() {
	}
	
	public function indexAction() {

//        $Images = new Imagick()
        $path = __DIR__.'/../../../public/uploads/20181217/20181217182207821.jpg';
        $image = new \Phalcon\Image\Adapter\GD($path);
        echo '<pre>';
        var_dump($image);
        var_dump($image->getImage());
        var_dump($image->getRealpath());
        var_dump($image->getWidth());
        var_dump($image->getHeight());

        var_dump($image->crop(200,200));
        var_dump($image->save(__DIR__.'/../../../public/uploads/20181217/1.jpg'));

        exit;


    }

}
