jQuery(window).ready(function(){
	jQuery('.toplist').bind('mouseenter',function(){
		jQuery('.toplist .row_2').hide();
		jQuery('.toplist .toplist_bg').hide();
		$(this).find('.row_2').show();
		$(this).find('.toplist_bg').show();
	});
	jQuery('#recommand .wrapper').bind('mouseenter',function(){
		$(this).css({'background-color':'#C4CDD2'});
		$(this).find('.rname').animate({height:40},100);
		$(this).find('.rname_wrapper').animate({height:42},100);
	}).bind('mouseleave',function(){
		$(this).css({'background-color':'transparent'});
		$(this).find('.rname').animate({height:24},100);
		$(this).find('.rname_wrapper').animate({height:27},100);
	});
	jQuery('#left_side .ru').bind('mouseenter',function(){
		jQuery('#updates_panel').show();
		jQuery('#new_panel').hide();
		jQuery(this).css({'background-color':'#B6B6B6'});
		jQuery('#left_side .rc').css({'background-color':'transparent'});
	});
	jQuery('#left_side .rc').bind('mouseenter',function(){
		jQuery('#updates_panel').hide();
		jQuery('#new_panel').show();
		jQuery(this).css({'background-color':'#B6B6B6'});
		jQuery('#left_side .ru').css({'background-color':'transparent'});
	});
	jQuery('#left_side .rc').bind('mouseenter',function(){
		jQuery('#updates_panel').hide();
		jQuery('#new_panel').show();
		jQuery(this).css({'background-color':'#B6B6B6'});
		jQuery('#left_side .ru').css({'background-color':'transparent'});
	});
	jQuery('<div id="scale_wrapper"></div>').appendTo(document.body);
	var scale_w = document.getElementById('scale_wrapper');
	jQuery('.box .img_wrapper').bind('mouseenter',function(){
		jQuery(scale_w).html('<img src="'+jQuery(this).find('img').attr('src')+'">');
		var o = jQuery(this).offset();
		jQuery(scale_w).css({left:o.left+110,top:o.top-1});
		jQuery(scale_w).show();
	}).bind('mouseleave',function(){
		jQuery(scale_w).hide();
	});
});