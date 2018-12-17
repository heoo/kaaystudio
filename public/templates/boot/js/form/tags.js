


/*
* @desc 删除标签
* @author 王鹏剑
* @date 2016-09-09 09:41
* */
function tdeltag(Obj)
{
    val = getVal(Obj);
    var ttags = $('#ttags');
    var tagid = $('#tagid');

    var t = ttags.val().replace(val.tagname,'');
    ttags.val(t);
    $(Obj).remove();

    var id = tagid.val().replace(val.tid,'');
    tagid.val(id);
}

/*
 * @desc 添加标签
 * @author 王鹏剑
 * @date 2016-09-09 09:41
 * */
function taddtag(Obj)
{
    val = getVal(Obj);
    var ttags = $('#ttags');
    var tagid = $('#tagid');
    if(ttags.val().indexOf(val.tagname)<0){
        $('#ttaglist').append('<span onclick="tdeltag(this);" data-key="'+val.tid+'" data-val="'+val.tagname+'">'+val.tagname+'</span>');
        ttags.val(ttags.val()+val.tagname);
        tagid.val(tagid.val()+val.tid);
    }
}
/*
* @desc 获取选中标签值
* @author 王鹏剑
* @date 2016-09-09 11:05
* */
function getVal(Obj)
{
    var Obj = $(Obj);
    var val = new Array();
    val.tagname = Obj.attr('data-val');
    val.tid   = Obj.attr('data-key');
    console.log(val.tid);
    return val;
}

/*
* @desc 新建标签
* @author 王鹏剑
* @date 2016-09-09 11:47
* */
function tagsAdd(){
    $.post(BASE_URL+'tagsadd',{tagname:$('#ttaginput').val()},function(result){
        console.log(result);
        var ttags = $('#ttags');
        var tagid = $('#tagid');
        if(ttags.val().indexOf(result.tagname)<0){
            $('#ttaglist').append('<span onclick="tdeltag(this);" data-key="'+result.tid+'" data-val="'+result.tagname+'">'+result.tagname+'</span>');
            ttags.val(ttags.val()+result.tagname);
            tagid.val(tagid.val()+result.tid);
        }
    },'JSON');
}
/*
 * @desc 标签检索
 * @author 王鹏剑
 * @date 2016-09-09 09:41
 * */
$(function(){
    $('#ttaginput').keyup(function(){
        searchkey=$(this).val();
        if(searchkey.length>=2){
            $.get(BASE_URL+'tag',{'tagname':searchkey},function(result){
                $('#tdata_list').html(result);
            });
        }
        else
        {
            $('#tdata_list').html('<a rel="no">请输入两个以上关键字...</a>');
        }
    }).focusout(function(){
        setTimeout(function(){
            $('#ti_data_list').hide(100);
        },100)}).click(function(){
        $('#ti_data_list').show();
    });
});
