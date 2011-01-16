jQuery(window).ready(function(){
	var series_url = series_name;
	var renderedPage = new Array();
	var showpicjob=null;
	renderedPage[current_page]=true;
	function next_page() {
		change_page(current_page+1);
	}
	
	function previous_page() {
		change_page(current_page-1);
	}

	function _setWidth(img){
		if(img.width()==0){
				setTimeout(function(){
					_setWidth(img);
				},200)}
			else
				jQuery("#pic_container").css({width:img.width(),height:img.height()});
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
			_setWidth(img);
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
		page_id = current_page;
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
			jQuery.get("/r/l_mp_pic"+series_url  + current_chapter + '/' + page_num + '.html',null,
				function(json){
					var img = jQuery("<img />",{
							id:"page"+page_num,
							src:json.src,
							title:json.title,
							style:"display:none"
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

	function change_chapter(value) {
		document.location = WEB_ROOT+"/r/l_manga"+series_url  + value + ".html";
	}
	function previous_chapter() {
		if (current_page <= 1 && current_chapter <= 1) {
			alert("this is the first chapter");
		} else {
			change_chapter(current_chapter - 1);
		}
		return false
	}
	function next_chapter() {
		if (current_page >= total_pages && current_chapter >= total_chapters) {
			alert("this is the last chapter");
		} else {
			change_chapter(current_chapter + 1);
		}
		return false;
	}
	function changeSelectValue(){
		jQuery("select[class='middle']").each(function(){
			if(this.options[current_page-1])
			this.options[current_page-1].selected=true;
		});
	}
	jQuery('.pager').addClass("page");
	jQuery('.pager').append('<div class="right middle"><a target="_self" class="button prev_page" href="javascript:void(0)"><span></span>pervious page</a>      <div class="left">Page        <select class="middle"></select>        of '+total_pages+'      </div>      <a target="_self" class="button next_page" href="###"><span></span>next page</a></div>');
	var _select = jQuery('select[class="middle"]');
	for(var i=0;i<total_pages;i++){
		_select.append(jQuery('<option value="'+(i+1)+'" '+((i==current_page-1)?"selected":"")+' >'+(i+1)+'</option>'));
	}
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
	jQuery(".next_page").bind('click',function(){
		next_page();
	});
	jQuery(".prev_page").bind('click',function(){
		previous_page();
	});
	jQuery("#top_chapter_list").bind('change',function(){
		change_chapter(parseInt(jQuery(this).val()));
	});
	jQuery("#bottom_chapter_list").bind('change',function(){
		change_chapter(parseInt(jQuery(this).val()));
	});
	jQuery("select[class='middle']").bind('change',function(){
		change_page(parseInt(this.options[this.selectedIndex].value));
	});
	var current_chapter = parseInt(jQuery('#top_chapter_list').val());
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
	change_page(current_page);
	chapter_id = current_chapter;
	page_id = current_page;
	if (current_chapter < total_chapters) {
		jQuery.get(WEB_ROOT+"/r/l_manga"+series_url  + (current_chapter + 1) + ".html");
	}
});