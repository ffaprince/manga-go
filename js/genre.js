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
	var showtipfun = function(e){
		jQuery('#genre_tip .tip-title').text(jQuery(this).text());
		jQuery('#genre_tip .tip-text').text(jQuery(this).attr('_title'));
		var t = jQuery('#genre_tip');
		t.css({   
				"top": (e.pageY+10) + "px",   
				"left": (e.pageX+5) + "px"  
			});
		jQuery(this).bind('mousemove',function(e){
			t.css({   
				"top": (e.pageY+10) + "px",   
				"left": (e.pageX+5) + "px"  
			});
		});
		t.show();
	};
	var hidetipfun = function(){
		jQuery(this).unbind('mousemove');
		jQuery('#genre_tip').hide();
	};
	jQuery('#genres').find('a').bind('mouseenter',showtipfun).bind('mouseleave',hidetipfun);
	jQuery('#genre_panel .tips').bind('mouseenter',showtipfun).bind('mouseleave',hidetipfun);
	jQuery('#comp_a').bind('click',function(){
		if((window.location.href+"").indexOf("?")>0)
			window.location=window.location.href+"&is_completed=1&advopts=1";
		else
			window.location=window.location.href+"?is_completed=1&advopts=1";
	});
	jQuery('<div id="hotbook_recommand" style="position:absolute;z-index:2"></div>').appendTo(document.body);
	var hotbook_rcd = document.getElementById('hotbook_recommand');
	jQuery('#hotbook a').bind('mouseenter',function(){
//		var t = jQuery(this);
//		var o = t.offset();
//		var hotbookimgs = "";
//		var content = '<div style="float:left;height:23px;font-size:14px;background-color:#F1EDEE">'+t.text()+'</div><div class="hotbook_rcd_content"><div class="left">[instructon]</div><div class="right">'+hotbookimgs+'</div></div>';
//		jQuery(hotbook_rcd).html(content).css({top:o.top,left:o.left});
	});

});