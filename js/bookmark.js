jQuery(window).ready(function(){
	
	var closeBookmarkPanelJob=null;
	var bmupdated = true;
	var login = true;

	function _createHistoryBm(bms){
		bms.reverse();
		var books = new Object();
		for(var i=0;i<bms.length;i++){
			if(books[bms[i]['manga_id']])
				books[bms[i]['manga_id']].push(bms[i]);
			else{
				books[bms[i]['manga_id']]=new Array(bms[i]);
			}
		}
		var html = "<div id='bookmark_li'><div id='bm_deltip'>deleted!</div><ul>";
		html+="<li class='bookmark_title'>Bookmarks</li>";
		var _h="";
		for(var mid in books){
			_h+="<li class='books'><a href='/r/l_manga/manga/"+mid+"/'>"+books[mid][0]['manga_name']+"</a></li>";
			for (var i=0;i<books[mid].length;i++){
				var bm=books[mid][i];
				var url = '/r/l_manga/manga/'+bm['manga_id']+"/";
				if(bm['chapter_id']!="") url+=bm['chapter_id']+"/";
				if(bm['page_id']!="") url+=bm['page_id']+".html";
				var name=bm['chapter_name']+"->page&nbsp;"+bm['page_id'];
				_h+="<li class='marks'><span class='left'><a href='"+url+"'>"+name+"</a></span><span class='right'><a title='delete it' class='del' _id='"+bm['add_time']+"' href='javascript:void(0)'><img src='/images/genre_exclude.png'></a></span><span class='right time'>"+bm['_time']+"</span></li>";
			}
		}
		if(_h=="") 
			_h="<li>you have no bookmarks yet.</li>";
		html+=_h+"</ul></div>";
		return html;
	}
	function _renderPanel(json){
		var p = jQuery('#bookmarkpanel');
		p.html("");
		if(json=='nologin'){
			p.html('<ul><li>please login first!</li></ul>');
			login=false;
			return;
		}
		obj = jQuery.parseJSON(json);
		p.html(_createHistoryBm(obj));
		jQuery('#bookmarkpanel .del').bind('click',function(){
			jQuery.post('/r/bookmark/del/',{add_time:jQuery(this).attr('_id')},function(json){
				_renderPanel(json);
				if(showbmdeltip) clearTimeout(showbmdeltip);
				jQuery("#bm_deltip").fadeIn(500,function(){
					showbmdeltip = setTimeout(function(){jQuery("#bm_deltip").fadeOut(500);},2000);
				});
			});
			jQuery("#bookmarkpanel").append("<div id='bm_del' class='bm_loadtip'></div>");
		});
	}
	function showBookmarkPanel(){
		if(bmupdated&&login){
			jQuery.get('/r/bookmark/get/',function(json){
				_renderPanel(json);
			});
		}
		bmupdated = false;
		var nav = jQuery('#nav_bookmark');
		jQuery('#bookmarkpanel').css({right:document.documentElement.clientWidth-nav.offset().left-nav.width(),top:jQuery('#nav_genre').offset().top+44}).slideDown(100);
	}
	
	function hideBookmarkPanel(){
		jQuery('#bookmarkpanel').slideUp(100,function(){
			jQuery("#bm_deltip").hide();
		});
	}
	jQuery("<div id='bookmarkpanel'><div id='bm_del' class='bm_loadtip'></div></div>").appendTo(document.body).hide();
	jQuery('#nav_bookmark,#bookmarkpanel').bind('mouseenter',function(){
		jQuery('#genres').hide();
		if(closeBookmarkPanelJob)
			clearTimeout(closeBookmarkPanelJob);
		showBookmarkPanel();
	}).bind('mouseleave',function(){
		closeBookmarkPanelJob = setTimeout(function(){
			hideBookmarkPanel();
		},500);
	});
	jQuery('#bookmark').bind('click',function() {
		jQuery.post('/r/bookmark/add/',{
				manga_id:manga_id,
				chapter_id:chapter_id,
				page_id:page_id,
				manga_name:manga_name,
				chapter_name:chapter_name
			},function(res){
				jQuery("#bm_add").remove();
				if(res=='nologin')
					alert('please login first!');
				else if(res=='max'){
					alert('you have reached the max limit 20!');
				}
				else{
					if(showbmtip) clearTimeout(showbmtip);
					jQuery("#bm_tip").fadeIn(500,function(){
						showbmtip = setTimeout(function(){jQuery("#bm_tip").fadeOut(500);},2000);
					});
					bmupdated=true;
				}
		});
		jQuery("#tool").append("<span id='bm_add' class='bm_loadtip'></span>");
	});
	var showbmtip=null,showbmdeltip=null;
	jQuery("#tool").append("<span id='bm_tip'>got it!</span>");
});