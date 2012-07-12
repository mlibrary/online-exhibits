<?php head(array('title' => html_escape(exhibit('title') . ' : '. exhibit_page('title')), 'bodyid'=>'exhibit','bodyclass'=>'show')); ?>
<div id="primary">
	<?php echo mlibrary_header_banner(); ?>
<!-- <h1><?php echo html_escape(exhibit('title')); ?></h1> -->
    
	<!-- load JS for Metadata collapse -->

    
    <div id="nav-container">
    <?php //echo exhibit_builder_section_nav('home'); ?>
    	<?php echo exhibit_builder_nested_nav(null,mlibrary_pages_in_section()); ?>
    <!--
    	php echo exhibit_builder_section_nav();
    	php echo exhibit_builder_page_nav();
        --->
    </div>

	<h2><?php echo exhibit_page('title'); ?></h2>

	<?php exhibit_builder_render_exhibit_page(); ?>
	
	<div id="exhibit-page-navigation">
	   	<?php echo exhibit_builder_link_to_previous_exhibit_page(); ?>
    	<?php echo exhibit_builder_link_to_next_exhibit_page(); ?>
	</div>
</div>	
<?php foot(); ?>