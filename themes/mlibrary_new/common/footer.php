	</div><!-- end content -->
</main>
	<footer class="site-footer">
			<div class="container container-medium">
				<div id="footer-content">
							<p><a href="">Home</a></p>
							<p><a href="">Help</a></p>
			        <?php if($footerText = get_theme_option('Footer Text')): ?>
			        <div id="custom-footer-text">
			            <p><?php echo get_theme_option('Footer Text'); ?></p>
			        </div>
			        <?php endif; ?>
			        <?php if ((get_theme_option('Display Footer Copyright') == 1) && $copyright = option('copyright')): ?>
			        <p><?php echo $copyright; ?></p>
			        <?php endif; ?>
			        <p><?php echo __('<a class="powered" href="http://omeka.org">Proudly powered by Omeka</a>'); ?></p>
				</div> <!-- end footer-content row -->
	    </div>

	     <?php fire_plugin_hook('public_footer', array('view'=>$this)); ?>
	</footer>

</body>

</html>
