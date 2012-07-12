<?php head(array('title' => html_escape(exhibit('title') . ' : '. exhibit_page('title')), 'bodyid'=>'exhibit','bodyclass'=>'show')); ?>
<div id="primary" class="cf">
	<?php echo mlibrary_header_banner(); ?>
    
	<!-- load JS for Metadata collapse -->

<?php if (mlibrary_exhibit_gallery() !="gallery") {?>
   
        <div id="nav-container">
            <div class="exhibit-overview">
                <?php echo link_to_exhibit('Introduction'); ?>
            </div>
            
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
	<?php } else{ ?>
	    
	        <div id="nav-container" class="gallary">
	    		<div class="exhibit-overview">
	    		<?php //echo exhibit_builder_nested_nav(null); ?>
	    			<?php echo link_to_exhibit('Introduction'); ?>
                    </div>
	    		<?php echo exhibit_builder_nested_nav(null,'false'); ?>
	    		
		    </div>
    <!--
    	php echo exhibit_builder_section_nav();
    	php echo exhibit_builder_page_nav();
        --->

		<h2><?php echo exhibit_page('title'); ?></h2>
	    <?php exhibit_builder_render_exhibit_page();?>
	    <?php }?>
	    
</div>	
<?php foot(); ?>
