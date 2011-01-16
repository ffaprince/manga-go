<div class="clear gap"></div>
</div><!--end page-->
<div id="footer">
	<div class="left">
		<p class="affiliates">
			<a title="Manga Go" target="_blank" href="/"><?=NAME?></a> | 
			<a title="Manga List" target="_blank" href="/r/l_directory/directory/">Directory</a> | 
			<a href="mailto:ffaprince@gmail.com">Contact US</a> | 
			<a href="/sitemap.html">sitemap</a> | 
			<a href="#top" onclick="self.scrollTo(0,0);return false;">Top</a>
		</p>
		<p id="copyright">
			All Manga, Character Designs and Logos are &copy; to their respective copyright holders. <br>
			&copy; 2010 <?=DOMAIN?>.com. Current Time is <?=date("T g:i a",time())?>.<br>
		</p>
	</div>
</div>

<script type="text/javascript" src="/r/mergesrc/alljs/?<?=JS_BUSTER?>"></script>
<script type="text/javascript" src="/r/jslink/media/js/keywords.js?v=<?=date('Ymd',time())?>"></script>
<script type="text/javascript" src="/js/search.js?<?=JS_BUSTER?>"></script>
<!-- FBJS Load -->
    <div id="fb-root"></div>
    <script>
		USER_NAME='<?=$_SESSION["userinfo"]["name"]?>';
        window.fbAsyncInit = function() {
            FB.init({
                appId : '<?=FACEBOOK_APP_ID?>', 
                status : true,
                cookie : true,
                xfbml : true
            });
			FB.Event.subscribe('auth.sessionChange', function(response) {
				if(response.status=='unknown'&&USER_NAME!=''){
					window.location.reload();
				}else if(response.status=='connected'&&USER_NAME==''){
					window.location.reload();
				}
			});
        };
        (function() {
            var e = document.createElement('script');
            e.async = true;
            e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js#xfbml=1';
            document.getElementById('fb-root').appendChild(e);
        }());        
    </script>
</body>
</html>
