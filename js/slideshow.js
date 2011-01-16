jQuery(window).ready(function(){
	var idx=0;
	function showBanner(index){
		jQuery('#idSelector').find('li').removeClass('on');
		jQuery(jQuery('#idSelector').find('li')[index]).addClass("on");
		jQuery('#idSlider').children().each(function(idx,b){
			var s = jQuery(b);
			var offset=0;
			if(parseInt(jQuery.browser.version)<8&&jQuery.browser.msie)
				offset=index;
			var top = index*s.height()*(-1)-offset;
			s.animate({top: top}, 200);
		});
	}
	jQuery('#idSelector').children().each(function(index,ele){
		jQuery(ele).bind('mouseover',function(){
			idx=index;
			showBanner(index);
		});
	});
	jQuery(jQuery('#idSelector').children()[0]).addClass('on');
	var len = jQuery('#idSelector').find('li').length;
	setInterval(function(){
		idx=idx>(len-1)?0:idx;
		showBanner(idx++)
	},5000); 

	//SHOWCASE
	var _t = jQuery('#sliderContent').find('li');
	var l=_t.length-3;
	var r=0;
	jQuery('#slider_next').bind('click',function(){
		if(l>0){
			jQuery(_t).animate({left:'-=166px'},200);
			l--;r++;
		}
	})
	jQuery('#slider_prev').bind('click',function(){
		if(r>0){
			jQuery(_t).animate({left:'+=166px'},200);
			r--;l++;
		}
	})
});