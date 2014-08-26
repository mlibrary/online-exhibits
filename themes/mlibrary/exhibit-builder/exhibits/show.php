<?php echo head(array(
                      'title' => html_escape(metadata('exhibit', 'title').' : '.metadata('exhibit_page', 'title')),
                      'bodyid'=>'exhibit',
                      'bodyclass' => 'show'));?>

<div id="primary">
 <?php echo mlibrary_header_banner(); ?> 
  <?php //if (mlibrary_exhibit_gallery()!='gallery'){?>
    <div id="nav-container">
	      <div class="exhibit-overview">
		        <?php echo link_to_exhibit('Introduction'); ?>
     	  </div>	
     	  <?php $exhibit_page = get_current_record('exhibit_page', false);?>     
	        <ul id="exhibit-pages">       
              <?php set_exhibit_pages_for_loop_by_exhibit(); ?>
              <?php foreach (loop('exhibit_page') as $exhibitPage): ?>
              <?php    echo mlibrary_exhibit_builder_page_summary($exhibitPage,$exhibit_page); ?>
              <?php endforeach;        
              // echo exhibit_builder_page_nav(); ?>       
         </ul>
    </div> <!--nav-container-->
    <h2> <?php set_current_record('exhibit_page', $exhibit_page);
               echo metadata('exhibit_page', 'title');
          ?>
    </h2>    
  <?php //} ?> 
  <!--<h2><span class="exhibit-page">--> <?php
	  echo exhibit_builder_render_exhibit_page($exhibit_page);
//echo metadata('exhibit_page', 'title'); ?><!--</span></h2>-->

	  <div id="exhibit-page-navigation">
            <?php echo exhibit_builder_link_to_previous_page("Previous Page"); ?>
            <?php echo exhibit_builder_link_to_next_page("Next Page"); ?>            
    </div>
    
</div>    
<?php echo foot(); ?>