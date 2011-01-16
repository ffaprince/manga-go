jQuery(window).ready(function(){
	var series_url = "/manga/" + series_name;
	var renderedPage = new Array();
	var showpicjob=null
	renderedPage[current_page]=true;
	function next_page() {
		change_page(current_page+1);
	}
	
	function previous_page() {
		change_page(current_page-1);
	}

	function show_pic(){
		if(!document.getElementById('page'+current_page)){
			showpicjob = setTimeout(show_pic,200);
		}else{
			var img = jQuery('#page'+current_page);
			jQuery('#pic_container').find("img").hide();
			img.addClass('pic');
			img.fadeIn(200);
			scroll(0, jQuery('#page').offset().top);
			jQuery("#pic_container").css({width:parseInt(img.attr('_width')),height:parseInt(img.attr('_height'))-35});
		}
	}

	function change_page(page_num){
		clearTimeout(showpicjob);
		if (page_num > total_pages) {
			next_chapter();
			return;
		}
		if (page_num < 1) {
			previous_chapter();
			return;
		}
		current_page=page_num;
		page_id=current_page;
		getImgEle(page_num,show_pic);
		if(page_num>1){
			getImgEle(page_num-1);
		}
		if(page_num<total_pages){
			getImgEle(page_num+1,_getImgEle,true);
		}
		changeSelectValue();
	}
	//load next pic
	function _getImgEle(page_num){
		if(page_num-current_page>5) return;
		if(!getImgEle(page_num+1,_getImgEle,true))
			return;
	}

	function getImgEle(page_num,callback,onload){
		if(!callback)
			callback=function(){};
		if(page_num>total_pages||page_num<1) return false;
		if(!renderedPage[page_num]){
			jQuery.get("/r/l_pic"+series_url + '/' + current_chapter + '/' + page_num + '.html',null,
				function(json){
					var img = jQuery("<img />",{
							id:"page"+page_num,
							src:json.src,
							title:json.title,
							style:"display:none",
							_width:json.width,
							_height:json.height
						});
					if(onload){
						img.bind('load',function(){
							callback(page_num);
						});
					}
					img.appendTo("#pic_container");
					if(!onload) callback(page_num);
				},'json');
			renderedPage[page_num]=true;
		}
		else
			callback(page_num);
		return true;
	}

	function change_chapter(c) {
		var t = c.options[c.selectedIndex].value;
		document.location = WEB_ROOT+"/r/l_manga"+series_url + "/" + t + "/";
	}
	function previous_chapter() {
		if (current_page <= 1 && current_chapter_index === 0) {
			document.location = WEB_ROOT+"/r/l_manga"+series_url + '/';
		} else {
			document.location = WEB_ROOT+"/r/l_manga"+series_url + '/' + (jQuery('#top_chapter_list')[0].options[current_chapter_index - 1].value) + '/last.html';
		}
		return false
	}
	function next_chapter() {
		if (current_page >= total_pages && current_chapter_index + 1 == total_chapters) {
			document.location = WEB_ROOT+"/r/l_manga"+series_url + '/';
		} else {
			document.location = WEB_ROOT+"/r/l_manga"+series_url + '/' + (jQuery('#top_chapter_list')[0].options[current_chapter_index + 1].value) + '/';
		}
		return false;
	}
	function changeSelectValue(){
		jQuery("select[class='middle']").each(function(){
			if(this.options[current_page-1])
			this.options[current_page-1].selected=true;
		});
	}
	jQuery("#pic_container").addClass('next');
	jQuery("#pic_container").bind('click', function(event){
		var offset = jQuery(this).offset();
		if(event.clientX - offset.left < jQuery(this).width()*0.38){
			previous_page();
		} else {
			next_page();
		}
	}).bind('mousemove', function(event){
			var th = jQuery(this);
            var offset = th.offset(),
                x      = event.clientX,
                y      = event.clientY;
            if(x >= offset.left && x < offset.left + th.width()*0.38){
				th.removeClass('next_pic').addClass('pre_pic');
            }
            if(x >= offset.left + th.width()*0.38 && x <= offset.left + th.width()) {
                th.removeClass('pre_pic').addClass('next_pic');
            }
        });
	jQuery("#image").attr("id","page"+current_page);
	change_page(current_page);
	jQuery(".next_page").bind('click',function(){
		next_page();
	});
	jQuery(".prev_page").bind('click',function(){
		previous_page();
	});
	jQuery("#top_chapter_list").bind('change',function(){
		change_chapter(this);
	});
	jQuery("#bottom_chapter_list").bind('change',function(){
		change_chapter(this);
	});
	jQuery("select[class='middle']").bind('change',function(){
		change_page(parseInt(this.options[this.selectedIndex].value));
	});
	var it=null;
	jQuery(document).bind('keydown',function(event){
		 var code = event.keyCode;
		 switch(code){
			 case 37: //left
				 current_page--;
				 if (current_page < 1) {
					 previous_chapter();
					 return;
				 }
				 changeSelectValue();
				 event.preventDefault();
				 break;
			 case 39: //right
				 current_page++;
				 if (current_page > total_pages) {
					 next_chapter();
					 return;
				 }
				 changeSelectValue();
				 event.preventDefault();
				 break;
			 default:
				 return;
		}
		 if(it)
			 clearTimeout(it);
		 it = setTimeout(function(){
			 change_page(current_page);
		 },200);
	}).bind('selectstart',function(){
		return false;
	}); 
	manga_name = jQuery('#series').text();
	manga_id = series_name;
	chapter_id = current_chapter;
	chapter_name = jQuery('#top_chapter_list option:selected').text();
	page_id=current_page;
	if (current_chapter_index + 1 < total_chapters) {
		jQuery.get(WEB_ROOT+"/r/l_manga"+series_url + '/' + (jQuery('#top_chapter_list')[0].options[current_chapter_index + 1].value) + '/');
	}
});