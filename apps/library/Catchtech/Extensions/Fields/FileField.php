<?php
/**
 * Created by PhpStorm.
 * User: othc
 * Date: 14-7-14
 * Time: 下午3:27
 * 文件上传
 */

namespace Catchtech\Extensions\Fields;


class FileField extends \Phalcon\Forms\Element
{
    public function render($attributes=null)
    {
        $src = "#";

        $displad = "none";
        if($this->getAttribute('value'))
        {
            $src = "/uploads/".$this->getAttribute('value');
            $displad = "inherit";
        }
        $html = '<input type="hidden" name="'.$this->getName().'"  id="'.$this->getName().'" value="'.$this->getAttribute('value').'" /> <input class="text-input small-input" type="file" name="photoname" id="photoname" /><img src="'.$src.'" id="uploadimgshow" style="display:'.$displad.';" height="120" width="160"/>';
        $html = $html.'<script> $(function() {
            $("#photoname").uploadify({
                \'swf\'           :   "/uploadify/uploadify.swf",
                \'uploader\'      :   "/js/uploadify/uploadify.php",
                \'cancelImg\'     :   "/js/uploadify/uploadify-cancel.png",
                  \'debug\'         :   false,
                \'buttonText\'    :   \'选择图片\',
                \'method\'        :   \'post\',
                \'buttonClass\'   :  \'upload_button\',
                \'fileTypeDesc\'  :   \'图片文件\',
                \'fileTypeExts\'  :   \'*.gif;*.jpg;*.png;*.bmp\',
                \'formData\'      :   \'\',
                \'multi\'         :   false,
                \'onUploadComplete\': function(file){
                },

                /**
                 * 上传成功后触发事件
                 */
                 \'onUploadSuccess\' : function(file, data, response) {
                    //参数data保存的是上传后的图片的路径
                    //alert(data);
                    //$(\'#photo\').css("background-color","#f00");
                    var path="/uploads/"+data;
                    $(\'#uploadimgshow\').attr("src",path);
                    $(\'#uploadimgshow\').show();
                    $(\'#'.$this->getName().'\').val(data);
                 }
});

});
</script>';

        return $html;
    }

} 