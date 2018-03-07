<?php
echo "Exhibit Image Gallery";
?>
<br> 
<?php
echo "Browse all images in this exhibit by section and by the order they appear within each section.";
echo "The University of Michigan Library has placed copies of these works online for educational and.";
echo "research purposes. For more information about using images from this exhibit please visit the Rights Statement page.";
?>
<br>

<?php 

set_exhibit_pages_for_loop_by_exhibit();
foreach (loop('exhibit_page') as $exhibitsection) {?>
    <?php
          echo $exhibitsection->title;
          if (!empty($exhibitsection->getAllAttachments())) {
mlibrary_new_display_item_card($exhibitsection->getAllAttachments());
              //display all images in section page first
               //foreach ($exhibitsection->getAllAttachments() as $attachment) {
                 //    $sectionpage_card_info = mlibrary_new_display_exhibit_section_page_cards($attachment);
                   // ?>
                  <!-- <div id = "exhibit-theme-item" class="panel panel-default">
                        <a href= <?php //echo html_escape($uri);?> >
                            <div class="panel-heading"><?php  //echo $sectionpage_card_info["image"];?></div>
                            <div class="card-info panel-body"><h3 class="panel-card-title"><?php //echo $sectionpage_card_info["title"];?></h3></div>
                        </a>
                     </div>-->
               <?php }
          // loop through subsections
          if (!empty($exhibitsection->getChildPages())){
              
                 foreach ($exhibitsection->getChildPages() as $child) {
                    if (!empty($child->getAllAttachments())) {
mlibrary_new_display_item_card($child->getAllAttachments());          
    //display all images in section page first
               //foreach ($child->getAllAttachments() as $attachment) {
                 //    $sectionpage_card_info = mlibrary_new_display_exhibit_section_page_cards($attachment);
                                  //$uri = exhibit_builder_exhibit_uri($exhibit, $exhibitPage);?>
                   <!--  <div id = "exhibit-theme-item" class="panel panel-default">
                        <a href= <?php //echo html_escape($uri);?> >
                            <div class="panel-heading"><?php  //echo $sectionpage_card_info["image"];?></div>
                            <div class="card-info panel-body"><h3 class="panel-card-title"><?php //echo $sectionpage_card_info["title"];?></h3></div>
                        </a>
                     </div>-->
               <?php }
           }
//                    release_object($child);
                 }
}
  
