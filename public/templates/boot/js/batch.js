// JavaScript Document

$(function(){
	
	$(".main_ls_box").on("click",function(){
        //alert('1');
        $(this).toggleClass('on');
    });

	$(".search_more").click(function(){
        //alert(2);
		$(".priceBox").toggle()
	})
	
    var num;
    $('.search_more').hover(function(){
       /*图标向上旋转*/
        $(this).children().removeClass().addClass('hover-up');
    },function(){
        /*图标向下旋转*/
        $(this).children().removeClass().addClass('hover-down');
    });

    

	//点击文本框获得焦点事件
    $('.msg_input').focus(function(event) {
        if($(this).hasClass('gray_text')){
            $(this).val('');
            $(this).removeClass('gray_text');
        }
    });
	//点击文本框失去焦点事件
    $('.msg_input').blur(function(event) {
        //alert("out2");
        if($(this).val()==''){
            //alert("in2");
            $(this).val($(this).attr('msg'));
            $(this).addClass('gray_text');
        }
    });

    //反选的特效
    $(".footer_btn").click(function(){
  		$('.main_ls_box').toggleClass('on');
	});


	$(".ls_content").hover(function(){
		var dd=$(this).text().length
		
		//alert(dd)
		if(dd>48){
			
			$(this).addClass('ls_content_hidden');
		    $(this).css('overflow','visible');
		}
        //$(this).addClass('ls_content_hidden');
		//$(this).css('overflow','visible');
    });
	$(".ls_content").mouseout(function(){
        $(this).removeClass('ls_content_hidden');
		$(this).css('overflow','hidden');
    });
    
	$(".ls_box_l_img ").mouseout(function(){
		$(this).find("img").css({"margin-left":"-25px"})
	})
	
	$(".noThrow").click(function(){
		$(".batck_alert").fadeToggle(300)
		$(".tan_dlg").fadeToggle(300)
	})
	$(".bat_delect").click(function(){
		$(".batck_alert").fadeOut(300)
		$(".tan_dlg").fadeOut(300)
	})
	//遮罩
	$('.open_dlg').click(function(e){
		$('body').css("overflow","hidden");
		$('.tan_dlg').fadeIn(300);
	});
	$('.close_dlg').click(function(e){
		$('body').css("overflow","auto");
		$('.tan_dlg').fadeOut(300);
	});
	
	


})



$(function(){
	var imgWid = 0 ;
	var imgHei = 0 ; //变量初始化
	var big = 5.5;//放大倍数
	$(".dddd").hover(function(){
	$(this).find("img").stop(true,true);
	var imgWid2 = 0;var imgHei2 = 0;//局部变量
	imgWid = $(this).find("img").width();
	imgHei = $(this).find("img").height();
	imgWid2 = imgWid * big;
	imgHei2 = imgHei * big;
	$(this).find("img").css({"z-index":999});
	$(this).find("img").css({"position":"absolute"});
	$(this).find("img").animate({"width":imgWid2 ,"height":imgHei2,"margin-left":-imgWid2/9,"margin-top":imgHei2/16});
	},function(){$(this).find("img").stop().animate({"width":imgWid,"height":imgHei,"margin-left":-imgWid/2,"margin-top":-imgHei/2,"z-index":1});$(this).find("img").css({"position":"relative"});});
	
})

//$(function(){
//	var imgWid = 0 ;
//	var imgHei = 0 ; //变量初始化
//	var big = 6;//放大倍数
//	$(".dddd img").hover(function(){
//		$(this).stop(true,true);
//		var imgWid2 = 0;var imgHei2 = 0;//局部变量
//		imgWid = $(this).width();
//		imgHei = $(this).height();
//		imgWid2 = imgWid * big;
//		imgHei2 = imgHei * big;
//		$(this).css({"z-index":999});
//		$(this).css({"position":"absolute"});
//		$(this).animate({"width":imgWid2 ,"height":imgHei2,"margin-left":-imgWid2/4,"margin-top":-imgHei2/13});
//		},function(){$(this).stop().animate({"width":imgWid,"height":imgHei,"margin-left":-imgWid/2,"margin-top":-imgHei/2,"z-index":1});$(this).css({"position":"relative"});});
//		
//
//
//
//})