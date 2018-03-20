
<section class="row">
  <div class="col-xs-12 col-sm-9">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo link_to_exhibit(metadata('exhibit','title',array('no_escape' => true)));?></li>
        <li class="breadcrumb-item active"><?php echo 'Exhibit Image'. metadata('exhibit_page','title',array('no_escape' => true)); ?></li>
      </ol>
  </div>
  <div class="col-xs-12 col-sm-3">
    <ul class="share-this">
      <li>Share this Gallery:</li>
      <li><a href="https://twitter.com/share" class="twitter-share-button" data-text="I just saw '<?php echo metadata('exhibit','title',array('no_escape' => true)); ?>' at the U-M Library Online Exhibits!" ><span class="sr-only">Tweet</span> </a></li>
    </ul>
   </div>
</section>

<div>
<?php echo "Exhibit Image Gallery";
echo "Browse all images in this exhibit by section and by the order they appear within each section.";
echo "The University of Michigan Library has placed copies of these works online for educational and.";
echo "research purposes. For more information about using images from this exhibit please visit the Rights Statement page.";?>
</div>

<?php set_exhibit_pages_for_loop_by_exhibit();

foreach (loop('exhibit_page') as $exhibitsection) {
$cards_in_subsection = '';
 ?>
 <section>  
  <?php $cards_in_section = mlibrary_new_get_cards_in_section_gallery($exhibitsection->getAllAttachments());
        echo implode($cards_in_section);
        if (!empty($exhibitsection->getChildPages())){
           foreach ($exhibitsection->getChildPages() as $child) {
             $cards_in_subsection = mlibrary_new_get_cards_in_section_gallery($child->getAllAttachments());              
             echo implode($cards_in_subsection);
           }
        } 
        
        if($cards_in_subsection || $cards_in_section) {
           // title for the section will be displayed if there are items in section or subsection
           echo $exhibitsection->title;
       }?>
  </section>
<?php }?>
  