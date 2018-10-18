<section class="exhibit record panel panel-default index-featured-exhibit">
           <?php $uri = exhibit_builder_exhibit_uri($exhibit);?>
              <a href= <?php echo html_escape($uri);?> >
                 <div class="panel-heading">
		      <?php if ($exhibitImage = record_image($exhibit,'original',array('alt' => '', 'class' => 'feature-banner-image'))):
	                       echo $exhibitImage;  
                            else:
                               echo '<img src="'.img("defaulthbg.jpg").'" alt="" />';
                            endif;
                      ?>
	        </div>
	        <div class="card-info panel-body">
		   <h3 class="panel-card-title"><?php echo metadata($exhibit, 'title');//exhibit_builder_link_to_exhibit($exhibit); ?></h3>    
		   <p class="panel-card-text"><?php echo snippet_by_word_count(metadata($exhibit, 'description', array('no_escape' => true))); ?></p>
	        </div>
              </a>
</section>
