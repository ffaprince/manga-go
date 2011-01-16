$(window).ready(function(){
	changeLoginStatus = function(logined){
		if(logined){
			jQuery('#login_btn').html("Hi,"+USER_NAME);
		}else
			jQuery('#login_btn').html('<fb:login-button perms="email"></fb:login-button>');
	}
//	$('#register_btn').bind('click',function(){
//		var windowWidth = document.documentElement.clientWidth;
//		var windowHeight = document.documentElement.clientHeight;
//		var popupHeight = $("#register_panel").height();
//		var popupWidth = $("#register_panel").width();
//		//centering
//		$("#register_panel").css({
//			"top": windowHeight/2-popupHeight/2,
//			"left": windowWidth/2-popupWidth/2
//		});
//		$('#register_panel').fadeIn('fast');
//	}).bind('mouseleave',function(){
////		$('#register_panel').fadeOut('fast');
//	});
});