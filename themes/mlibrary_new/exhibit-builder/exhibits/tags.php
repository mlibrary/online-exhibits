  <div class="col-sm-12 tag-cloud">
     <li>Browse by Popular Tags</li>
      <?php echo nav(array(
                array(
                    'label' => __('Show All Tags'),
                    'uri' => url('exhibits/browse?tags=all')
                )
            )
        ); ?>
  </div>

<ul class="tag-list"><?php echo tag_string($tags,'exhibits/browse');?></ul>
