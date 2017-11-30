<?php 
	echo head(array('bodyid'=>'home')); 
	$sidebar_pos=get_theme_option('sidebar_position');
	$main_add="";
	$sidebar_add="";
	if($sidebar_pos=='left'){
		$main_add=" col-md-push-4";
		$sidebar_add=" col-md-pull-8";
		}		
?>
<div class="row">
    <div class="col-md-8<?php echo $main_add;?>">
		<?php if ($homepageText = get_theme_option('Homepage Text')): ?>
			<div id="homepage-text"><p><?php echo $homepageText; ?></p></div>
		<?php else: ?>
		<?php echo $this->partial('common/loremize-main.phtml');?>
		<?php endif; ?>

    </div>

         <nav id="top-nav" class="top" role="navigation">
            <?php echo public_nav_main(); ?>
        </nav>
    
    <div class="col-md-4<?php echo $sidebar_add;?>">
		<?php if (get_theme_option('display_sidebar_menu') !== '0'): ?>
			<?php echo public_nav_sidebar_bootstrap(); ?>
		<?php endif; ?>
		<?php if (get_theme_option('display_sidebar_advanced_search') !== '0'): ?>
			<div id="search-container">
				<h2>Search</h2>
				<?php echo search_form(array('show_advanced' => true)); ?>
			</div>
		<?php endif; ?>		
        <?php if (get_theme_option('Display Featured Item') !== '0'): ?>
			<div class="panel panel-default">
				<div class="panel-heading"><span class="lead"><?php echo __('Featured Item'); ?></span></div>
				<div class="panel-body"><?php echo random_featured_items(1); ?></div>
			</div>           
        <?php endif; ?>		
        <?php if (get_theme_option('Display Featured Collection') !== '0'): ?>
			<div class="panel panel-default">
				<div class="panel-heading"><span class="lead"><?php echo __('Featured Collection'); ?></span></div>
				<div class="panel-body"><?php echo random_featured_collection(); ?></div>
			</div>
        <?php endif; ?>
        <?php if ((get_theme_option('Display Featured Exhibit') !== '0') && plugin_is_active('ExhibitBuilder') && function_exists('mlibrary_exhibit_builder_display_random_featured_exhibit')): ?>
              <?php echo mlibrary_exhibit_builder_display_random_featured_exhibit(); ?>           
        <?php endif; ?>
    </div>
</div>
       <div>
         <?php
           if (plugin_is_active('ExhibitBuilder') && function_exists('recent_exhibits_bootstrap')) { 
              echo recent_exhibits_bootstrap(4);
           }?>
       </div>
    <div>
      <a href="<?php echo url('exhibits'); ?>">Browse All Exhibits</a>
    </div>
</div>    

<?php fire_plugin_hook('public_home', array('view' => $this)); ?>

<?php echo foot(); ?>

