<?php 
  set_theme_option('display_header','1');
	echo head(array('bodyid'=>'home')); 
?>
<div class="row">
    <div class="col-xs-12">
    <div class="row detail-nav">
    	<div class="col-xs-12 ">
    		<div class="col-xs-12 col-sm-6">
    			<ul>
    			<li class="detail-nav-heading">Browse</li>
    			<li>Explore and discover inspiring collections of art, literature, culture, and history.</li>
    			<li><a href="<?php echo url('exhibits'); ?>">Browse online exhibits</a></li>
    			</ul>
	    	</div>
	    	<div class="col-xs-12 col-sm-6">
	    		<ul>
    			<li class="detail-nav-heading">About</li>
    			<li>The University of Michigan Library builds compelling digital exhibits using resources from our extensive collections in our Online Exhibits.</li>
    			<li><a href="<?php echo url('about'); ?>">About online exhibits</a></li>
    			</ul>
	    	</div>
    	</div>
    </div>
    <div class="col-xs-12 ">
      <div class="detail-nav-border"></div>
    </div>
    </div>

    <div class="col-xs-12">
      <h2 class="text-center">Featured Exhibits</h2>
    </div>
    <div class="col-xs-12">	
        
        <?php echo mlibrary_new_exhibit_builder_display_random_featured_exhibit(); ?>           
     </div>
  
       <div class="col-xs-12 index-exhibits-container">
         <?php
           if (plugin_is_active('ExhibitBuilder') && function_exists('recent_exhibits_bootstrap')) { 
              echo recent_exhibits_bootstrap(6);
           }?>
       </div>

  </div>

    <div class="browse-index">
      <div class="button browse-btn"><a href="<?php echo url('exhibits'); ?>">See All Exhibits</a></div>
    </div>
    
</div>    
</div>

<?php fire_plugin_hook('public_home', array('view' => $this)); ?>

<?php echo foot(); ?>

