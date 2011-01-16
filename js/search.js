jQuery(window).ready(function(){
	function searchBook(v){
		var books = new Array();
		for(var i=0;i<items.length;i++){
			if(v!=""&&items[i].toLowerCase().indexOf(v.toLowerCase())>=0){
				books.push(items[i]);
				if(books.length>=10) break;
			}
		}
		return books;
	}
	var keyupjob = null;
	function onkeyup(){
		if(keyupjob)
			clearTimeout(keyupjob);
		var input=this;
		keyupjob = setTimeout(function(){
			var books = searchBook(input.value);
			showBooksList(books,input.value,input.id);
		},100);
	}
	
	function showListPanel(id){
		var input = jQuery('#'+id);
		var o = input.offset();
		jQuery('#search_result').css({left:o.left,top:o.top+input.height()+5}).fadeIn(100);
	}

	function hideListPanel(){
		jQuery('#search_result').fadeOut(100);
	}

	function showBooksList(books,v,id){
		if(books.length==0){
			hideListPanel();
			return;
		}
		jQuery('#search_result').html("");
		var reg=new RegExp("("+v+")","gi");
		for(var i=0;i<books.length;i++){
			var li = jQuery("<li>").appendTo('#search_result').bind('mouseover',function(){
				jQuery(this).addClass('autocompleter-selected');
			}).bind('mouseout',function(){
				jQuery(this).removeClass('autocompleter-selected');
			}).bind('click',function(){
				if(hideListJob) clearTimeout(hideListJob);
				jQuery('#'+id).val(jQuery(this).attr('_value'));
				jQuery('#'+id).focus();
				hideListPanel();
			}).attr('_value',books[i]);
			jQuery("<span>"+books[i].replace(reg,"<span class='autocompleter-queried'>$1</span>")+"</span>").appendTo(li);
		}
		showListPanel(id);
	}
	var hideListJob=null;
	jQuery('#lookupwords').bind('keyup',onkeyup).blur(function(){
		hideListJob = setTimeout(hideListPanel,100);
	});
	jQuery('#searchform_name').bind('keyup',onkeyup).blur(function(){
		hideListJob = setTimeout(hideListPanel,100);
	});
	jQuery('<ul id="search_result" class="autocompleter-choices"></ul>').appendTo(document.body);
//	var searchkey = jQuery('#searchform_name').val();
//	if(searchkey){
//		var keys = searchkey.split(" ");
//		for(var i=0;i<keys.length;i++){
//			var reg=new RegExp(searchkey,"gi");
//			jQuery('.manga_open').each(function(idx,ele){
//				ele.innerHTML = ele.innerHTML.replace(reg,'<font style="color: red; background:yellow;"><b>'+searchkey+'</b></font>');
//			});
//			jQuery('.manga_close').each(function(idx,ele){
//				ele.innerHTML = ele.innerText.replace(reg,'<font style="color: red; background:yellow;"><b>'+searchkey+'</b></font>');
//			});
//		}
//	}
});