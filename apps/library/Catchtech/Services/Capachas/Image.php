<?php
namespace Catchtech\Capachas{

/**
 * Date: 13-10-10
 * Time: 下午4:22
 * 用来生成图片验证码的服务
 * @author: haowei
 * @version :1.0 *
 */

    class  Image extends \Phalcon\DI\Injectable {

        const CODE_VALID_SUCCESS = true;
        const CODE_VALID_FAILD = false;
        const ERROR_CODE_NOFONNDFONTFILE = '没有找到字体文件';



        private $_chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';

        private $_fontFile;
        private $_fontSize = FALSE;
        private $_fontColor = null;
        private $_bgColor = null;
        private $_noiseColor = null;


        function __construct($config){


            if (isset($config->fontFile)){
                $this->_fontFile = $config->fontFile;
            }else{
                throw new \Phalcon\Exception(self::ERROR_CODE_NOFONNDFONTFILE);
            }

            $this->_bgColor = isset($config->bgColor)?$config->bgColor:array(255,255,255);
            $this->_fontColor = isset($config->fontColor)?$config->fontColor:array(20,40,100);
            $this->_noiseColor = isset($config->noiseColor)?$config->noiseColor:array(100,120,180);


        }

        /**
         * @param array $bgColor
         */
        public function setBgColor($bgColor)
        {
            $this->_bgColor = $bgColor;
        }

        /**
         * @return array
         */
        public function getBgColor()
        {
            return $this->_bgColor;
        }

        /**
         * @param array $fontColor
         */
        public function setFontColor($fontColor)
        {
            $this->_fontColor = $fontColor;
        }

        /**
         * @return array
         */
        public function getFontColor()
        {
            return $this->_fontColor;
        }

        /**
         * @param mixed $fontFile
         */
        public function setFontFile($fontFile)
        {
            $this->_fontFile = $fontFile;
        }

        /**
         * @return mixed
         */
        public function getFontFile()
        {
            return $this->_fontFile;
        }

        /**
         * @param boolean $fontSize
         */
        public function setFontSize($fontSize)
        {
            $this->_fontSize = $fontSize;
        }

        /**
         * @return boolean
         */
        public function getFontSize()
        {
            return $this->_fontSize;
        }

        /**
         * @param array $noiseColor
         */
        public function setNoiseColor($noiseColor)
        {
            $this->_noiseColor = $noiseColor;
        }

        /**
         * @return array
         */
        public function getNoiseColor()
        {
            return $this->_noiseColor;
        }

        /**
         * @param string $_chars
         */
        public function setChars($_chars)
        {
            $this->chars = $_chars;
        }

        /**
         * @return string
         */
        public function getChars()
        {
            return $this->chars;
        }

        /**
         * 生成验证码图片
         * @param int $length 验证码长度
         * @param int $width
         * @param int $height
         *
         */
        public function generate($length,$width,$height){

            $code = $this->_code($length);

            $this->session->set("vercode",$code);

            if(!$this->_fontSize)
            {
                if($width > $height)
                {
                    $this->_fontSize = $height * 0.75;
                }
                else
                {
                    $this->_fontSize = $width * 0.75;
                }
            }

            // Create image
            $image = imagecreate($width,$height) or die('没有找到安装GD库');

            // set the colors
            $bgColor = imagecolorallocate($image,$this->_bgColor[0],$this->_bgColor[1],$this->_bgColor[2]);
            $text_color = imagecolorallocate($image,$this->_fontColor[0],$this->_fontColor[1],$this->_fontColor[2]);
            $noise_color = imagecolorallocate($image,$this->_noiseColor[0],$this->_noiseColor[1],$this->_noiseColor[2]);

            // Generate random dots in background
            for($i=0;$i<($width*$height)/3;$i++)
            {
                imagefilledellipse($image,mt_rand(0,$width),mt_rand(0,$height),1,1,$noise_color);
            }

            // Generate random lines in background
            for($i=0;$i<($width*$height)/150;$i++)
            {
                imageline($image,mt_rand(0,$width),mt_rand(0,$height),mt_rand(0,$width),mt_rand(0,$height),$noise_color);
            }

            // create textbox and add text
            $textbox = imagettfbbox($this->_fontSize,0,$this->_fontFile,$code) or die('Error in imagettfbbox function');
            $x = ($width - $textbox[4])/2;
            $y = ($height - $textbox[5])/2;
            imagettftext($image,$this->_fontSize,0,$x,$y,$text_color,$this->_fontFile,$code) or die('Error in imagettftext function');

            // Output captcha image to browser
            header('Content-Type:image/jpeg');
            imagejpeg($image);
            imagedestroy($image);

            return $this;
        }


        /**
         * 验证码编码
         */

        protected function _code($len)
        {
            $code = '';
            $i = 0;

            while($i < $len)
            {
                $char = substr($this->chars, mt_rand(0, strlen($this->chars) - 1), 1);
                $code .= $char;
                $i++;
            }

            return $code;
        }

        /**
         * 检验验证码
         * @param String $varcode
         */
        public function validate($vercode){

            if ($vercode == $this->session->get("vercode")){
                return self::CODE_VALID_SUCCESS;
            }else{
                return self::CODE_VALID_FAILD;
            }
        }

    }
}



