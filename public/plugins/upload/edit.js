$(function(){

    $('li.state-complete').hover(function(){
        $(this).find('.file-panel').animate({height:"30px"});
    },function(){
        $(this).find('.file-panel').animate({height:"0px"});
    });
    $('.cancel').click(function(){
        $('#thumb').val('')
        $(this).parent().parent().remove();
    });

});