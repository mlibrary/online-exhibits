<section class ="exhibit record panel panel-default index-exhibits">
                 <?php $uri = exhibit_builder_exhibit_uri($exhibit);?>
                  <a href= <?php echo html_escape($uri);?> >
		   <div class="panel-heading">
		     <?php echo $exhibitImage;?>
		   </div>
		   <div class ="card-info panel-body">
	  	     <h3 class="panel-card-title">
                         <?php echo strip_formatting($title);?>
                     </h3>
		   </div>
                </a>
</section>
