jQuery(window).ready(function(){
	var closeGenresPanelJob = null;
	function showGenresPanel(){
		var nav = jQuery('#nav_genre');
		jQuery('#genres').css({left:jQuery('#m').offset().left+3,top:nav.offset().top+44}).slideDown(100);
	}
	function hideGenresPanel(){
		jQuery('#genres').slideUp(100);
	}
	jQuery('#genres,#nav_genre').bind('mouseenter',function(){
		jQuery('#bookmarkpanel').hide();
		if(closeGenresPanelJob)
			clearTimeout(closeGenresPanelJob);
		showGenresPanel();
	}).bind('mouseleave',function(){
		closeGenresPanelJob = setTimeout(function(){
			hideGenresPanel();
		},500);
	});
	jQuery('<div id="genre_tip" class="tip"><div class="tip-title"></div><div class="tip-text"></div></div>').appendTo(document.body);
	jQuery('#genres').find('a').bind('mouseover',function(){
		jQuery('#genre_tip .tip-title').text(jQuery(this).text());
		jQuery('#genre_tip .tip-text').text(jQuery(this).attr('_title'));
		jQuery(this).bind('mousemove',function(e){
			jQuery('#genre_tip').css({   
				"top": (e.pageY+10) + "px",   
				"left": (e.pageX+5) + "px"  
			});
		});
		jQuery('#genre_tip').show();
	}).bind('mouseout',function(){
		jQuery(this).unbind('mousemove');
		jQuery('#genre_tip').hide();
	}).bind('click',function(){
		window.location=WEB_ROOT+'/r/l_genre/'+jQuery.trim(jQuery(this).text())+"/";
	});
	jQuery('#comp_a').bind('click',function(){
		if((window.location.href+"").indexOf("?")>0)
			window.location=window.location.href+"&is_completed=1&advopts=1";
		else
			window.location=window.location.href+"?is_completed=1&advopts=1";
	});
});