function checkusername(user){ 
	var username = user.value;  
	if(username == ''){
		$('#checkuser_1').html('请填写您的姓名！');
		return;
	}else if(username.match(/<|"/ig)) {
		$('#checkuser_1').html('用户名包含敏感字符！');
		return;
	}else{
		$('#checkuser_1').html('');
		return;
	}
}
function checkmail(){
	var email = $("input[name='email']").val()
	if(email == ''){
		Alert('请填写您的邮箱！');
		return false;
	}else if(email.match(/<|"/ig)){
		Alert('Email 包含敏感字符！');
		return false;
	}else if(!email.match(/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/)){
		Alert('Email 地址无效！');
		return false;
	}else{
		// $('#checkuser_2').html('');
		return true;
	}	 
}
function checksubmit(){
	var name = $("input[name='name']").val()
	var email = $("input[name='email']").val()
	var contents = $("textarea[name='contents']").val()
	if( name!='' && email!='' && contents!=''){
		if(checkmail()){
			$.post(BASE_URL+'send',{name : name,email :	email,messages:	contents},
				function(msg,b){
				console.log('msg-----'+msg)
					console.log(b)
					if(msg === ''){
						$("#success").load('success.html');
					}else{
						Alert(msg)
					}
				}
			);
		}
	}else{
		Alert('请完善您的信息，便于我们更准确的联系到您！');
		return false
	}
}