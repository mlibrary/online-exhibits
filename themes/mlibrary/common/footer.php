</div><!-- end content -->

<div id="footer">
	<!-- if user has installed myOmeka plugin, the user status login/register link will be activated in the footer
	This is not an ideal placement of the function but it should be ok for projects with smaller user base (eg class projects)-->
	<?php if (function_exists('my_omeka_user_status')) { echo '<div id="myomeka-status" style="float: right;">'.my_omeka_user_status().'</div>';}?>

    <p>&copy; <?php echo date(Y);?> <?php echo html_escape(settings('author'));?> &nbsp; Proudly powered by <a href="http://omeka.org/">Omeka</a><?php //echo mlibrary_display_theme_credit();?></p>

    <br/>
	
</div><!-- end footer -->
</div><!-- end wrap -->








<!-- Share Button Javascripts -->


<!-- the following social network scripts only load on the summary and item pages -->

	<?php if($GLOBALS['bodyclass'] == 'summary' || 'show item'){
	
		//Google Plus
		$output = '<script type="text/javascript">(function() {var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;po.src = "https://apis.google.com/js/plusone.js";var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);})();</script>';
		//Facebook
		$output .= '<div id="fb-root"></div><script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));</script>';
		//Twitter
		$output.= '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
		
		echo $output;
	}?>
	
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1341620-8']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
    

</body>

</html>
