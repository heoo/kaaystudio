
        /*编辑数据*/
        function editForm(){
            var data = $("#editSave").serialize();
            $.ajax({
                 url:BASE_URL+"edit",
                 type:"POST",
                 data:data,
                 success:function(msg){
                     var msgObj= jQuery.parseJSON(msg);
                     if(msgObj.errorNo != 00000){
                         alert("修改失败！");
                     }
                     if(msgObj.page=='undefined'){
                         window.location.href=BASE_URL+'list';
                     }else{
                         window.location.href=BASE_URL+'list?page='+msgObj.page;
                     }
                 }
             });
        }