<?php 
if (metadata('simple_pages_page', 'text', array('no_escape'=>true))) {
   echo metadata('simple_pages_page', 'text', array('no_escape'=>true));
}
else {?>
<section class="about-online-exhibits">
 <div>
   <?php $about_universityofmichigan = "<h1><strong>Digital Exhibits at the University of Michigan</strong></h1>
                                        <p>The University of Michigan Library builds compelling digital exhibits using resources 
                                                from our extensive collections in our Online Exhibits. You will find inspiring, curated 
                                                collections of art, literature, culture, history, and more!</p>
                                                <p>Online Exhibits are collections of digital objects &ndash; images, audio files, 
                                                   video files &ndash; that have been selected and organized around 
                                                   a clear, single focus. Like a case or panel in a physical exhibit, the displays 
                                                   in our Online Exhibits contain one or several objects that have been 
                                                   selected, organized, described, and explained by the curator/creator.</p>";

         $about_online_exhibits = "<p>An Online Exhibit may or may not have a physical counterpart, but are often created to provide ongoing 
                                           representations of physical exhibits that happen the library.</p>
                                        <p>University of Michigan Library Online Exhibits are proudly brought to you by <a href='https://omeka.org/'>Omeka</a>.</p>
                                       &nbsp ";

          $about_using_images = "<h2><strong>Using images from this website</strong></h2>
                                      <p>The University of Michigan Library has placed copies of these works online for educational and research purposes. 
                                         These works may be under copyright. If you decide to use any of these works, you are responsible for making your own 
                                         legal assessment and securing any necessary permission.</p>";

          $contact_email = "<p> If you have questions about the Online Exhibit web site or 
                                the inclusion of an item in an exhibit, please contact <a href='mailto:ask-omeka@umich.edu'>ask-omeka</a>
                            </p>";

    echo $about_universityofmichigan.$about_online_exhibits.$about_using_images.$contact_email; 
?>
</div>
</section> 
<?php } ?>
