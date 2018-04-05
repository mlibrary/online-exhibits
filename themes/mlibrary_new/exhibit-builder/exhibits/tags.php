  <div class="col-sm-12 tag-cloud">
  	<ul>
     <li>Browse by Popular Tags</li>
   	</ul>
  </div>
  <ul class="tag-list">
      <?php
         echo str_replace(';', '',tag_string($tags,'exhibits/browse'));?>
  </ul>
