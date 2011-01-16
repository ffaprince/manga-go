<? include( 'top.php');
?>
<div id="header_wrapper">
<div id="header">
    <div id="logo">
    	<a href="/"><img src="/images/logo.png" alt="Manga Go"></a>
    </div>
	 <div id="search">
		<form method="get" class="left" id="lookupform" action="/r/l_search/search.php">
			<fieldset class="left">
				<input type="text" onblur="if(this.value==''){this.value=this.defaultValue;}"
				onclick="if(this.value==this.defaultValue){this.value='';}"
				id="lookupwords" class="box" maxlength="100" value="Enter Manga Name..."
				name="name" autocomplete="off">
				<button type="submit"> </button>
			</fieldset>
		</form>
    </div>
    <div class="clear"></div>
      
	<div id="menu">
    	<div id="h"><a href="/" <?=$index_selected?"class='selected'":""?>><span></span> </a></div>
      	<div id="m"><a href="/r/l_directory/directory/" <?=$directory_selected?"class='selected'":""?>><span></span> </a></div>
      	<div id="c"><a href="/r/l_completed/"  <?=$completed_selected?"class='selected'":""?>><span></span></a></div>
      	<div id="nav_genre"  <?=$genre_selected?"class='selected'":""?>><a href="/r/l_genre/all/"><span></span></a></div>
        <div id="r">
        	<span class='user'><?createLoginButton();?></span><span class='bm'><a href="javascript:void(0);" id="nav_bookmark">Bookmark</a></span>
        </div>
    </div>
    
    <div class="clear"></div>
    
  	<div id="nave">
    <ul id="hotbook">
    	<?createHotGenre();?>
    </ul>
    <ul id="hotgenre">
        <li>Hot Manga:</li>
        <?createHotManga();?>
    </ul>
    </div>

</div>
</div>

<?include("genres.php")?>
    
    <div style="position: relative;" id="page" class="widepage">
