$(function(){
    /* 复选框*/
    $('input[name="checkbox"]').click(function(event) {
        if($(this).attr("checked"))
        {
            $(this).attr("checked", false);
            event.stopPropagation()
        }
        else
        {
            $(this).attr("checked", true);
            event.stopPropagation()
        }
    });

    /* 添加 */
    $("#add").click(function(){
        var url ='';
        if($('#ctype').val())
        {
            url = '?ctype='+$('#ctype').val();
        }
        window.location.href=BASE_URL+'add'+url;
    });

    /* 编辑 */
    $('#edit').click(function(){
        var code;
        var page;
        console.log($(this).data('options'));
        $("input[name='checkbox'][checked]").each(function(){
            code = $(this).data('options').code;
        });
        if(code)
        {
            var url ='';
            if($('#ctype').val())
            {
                url = '&ctype='+$('#ctype').val();
            }
            page = $("input[name='page']").val();
            window.location.href=BASE_URL+'edit?code='+code+'&page='+page+url;
        }
        else
        {
            alert('请选择要编辑的信息');
        }
    });

    /* 修改密码 */
    $('#reset').click(function(){
        var code;
        var page;
        console.log($(this).data('options'));
        $("input[name='checkbox'][checked]").each(function(){
            code = $(this).data('options').code;
        });
        if(code)
        {
            page = $("input[name='page']").val();
            window.location.href=BASE_URL+'reset?code='+code+'&page='+page;
        }
        else
        {
            alert('请选择要修改的信息');
        }
    });


    /* 根据Code单个删除 */
    $('#delete').click(function(){
        var code;
        $("input[name='checkbox'][checked]").each(function(){
            code = $(this).data('options').code;
        });

        if(code)
        {
            $.ajax({
                url:BASE_URL+"delete",
                type:'POST',
                data:"code="+code,
                success:function(msg){
                    var msgObj= jQuery.parseJSON(msg);
                    if(msgObj.errorNo != 00000){
                        alert("删除失败！");
                    }
                    window.location.reload();
                }
            });

        } else
        {
            alert('请选择要删除的信息');
        }
    });

});

