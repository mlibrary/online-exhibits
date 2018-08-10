  <div class="col-sm-12">
     <h2 class="browse-tag--heading">Browse by Popular Tags</h2>
  </div>
  <div class="col-sm-12 tag-list">
  	<ul>
      <?php
         echo str_replace(';', '',tag_string($tags,'exhibits/browse'));?>
    </ul>
  </div>
