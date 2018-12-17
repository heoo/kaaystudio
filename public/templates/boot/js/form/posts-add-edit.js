$(function(){

    $('li.state-complete').hover(function(){
        $(this).find('.file-panel').animate({height:"30px"});
    },function(){
        $(this).find('.file-panel').animate({height:"0px"});
    });
    $('.cancel').click(function(){
        var delImg = $(this).attr('data-rel');

        var delType = $(this).attr('data-type');
        if(delType == 'images'){

            var nVal = $('#multi-thumb').val();
            var nPath = nVal.replace(delImg,'');
            $('#multi-thumb').val(nPath)
        }else{
            $('#thumb').val('')
        }

        $(this).parent().parent().remove();
    });

});