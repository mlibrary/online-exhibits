  <div class="col-sm-12">
  	<ul>
     <li>Browse by Popular Tags</li>
   	</ul>
  </div>
  <div class="col-sm-12 tag-list">
      <?php
         echo str_replace(';', '',tag_string($tags,'exhibits/browse'));?>
  </div>
