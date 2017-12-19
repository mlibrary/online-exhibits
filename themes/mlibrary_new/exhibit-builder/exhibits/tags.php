<div id="primary"  class="browse tags" >
 <h1>Browse by Tag</h1>
   <ul class="navigation exhibit-tags" id="secondary-nav">
      <?php echo nav(array(
                array(
                    'label' => __('Browse All Tags'),
                    'uri' => url('exhibits/browse?tags=all')
                )
            )
        ); ?>
   </ul>
</div>
      <?php echo tag_string($tags,'exhibits/browse');?>










