	</div><!-- end content -->
</main>
	<footer class="site-footer" role="contentinfo">
			<div class="container container-medium">
				<div id="footer-content">
			        <p>©<?php echo date('Y'); ?> Regents of the University of Michigan. For details and exceptions, see the <a href="https://www.lib.umich.edu/library-administration/library-copyright-statement">Library Copyright Policy</a>.</p>
                    <p>We welcome your <a href="mailto:ask-omeka@umich.edu">comments, questions or feedback</a>.</p>
				</div> 
	    </div>
<?php fire_plugin_hook('public_footer', array('view'=>$this)); ?>
	</footer>
 
        <script id="twitter-wjs" type="text/javascript" async defer src="//platform.twitter.com/widgets.js"></script>

        <script type="text/javascript">
    var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-1341620-8']);
        _gaq.push (['_gat._anonymizeIp']);        
        _gaq.push(['_trackPageview']);

    (function() {
         var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
             ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
         var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
         })();
  </script>

</body>
</html>
