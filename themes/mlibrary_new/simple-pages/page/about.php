 <div>
<section>
  <!--Breadcrumb and Share Bar-->
 <div class="col-xs-12">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo link_to_home_page(__('Home')); ?></li>
        <li class="breadcrumb-item active">About Online Exhibits</li>
      </ol>
  </div>
</section>

<section>
  <div class="col-xs-12 about-online-exhibits">
   <?php $about_main = "<h1>Online Exhibits at the University of Michigan</h1>
            <p>The University of Michigan Library develops engaging and innovative online exhibits using resources from our extensive collections and the wide-range of subject expertise among the Library staff. In our online exhibits, we curate, describe, and interpret items with carefully researched analysis. As public scholarship, our online exhibits seek to foster critical thinking, share our unique and diverse collections to a global audience, and encourage discussion. We also support campus teaching and learning, working with classes and student researchers to utilize our rich collections. The University of Michigan Library's online exhibits inspire curiosity and prompt new paths of inquiry and are one of the many ways that we fulfill the <a href='https://www.lib.umich.edu/about-us/about-library/mission-and-values'>Library's mission, values, and strategic objectives</a>.</p>
            <p>If you are a faculty member or instructor interested in working with us for one of your classes or students, please email <a href='mailto:ask-omeka@umich.edu'>ask-omeka@umich.edu</a>.</p>
            <p>University of Michigan Library Online Exhibits are brought to you by <a href='https://omeka.org/'>Omeka</a>.</p>";

          $about_using_images = "<h2 id='image-rights'>Using images from this website</h2>
                                      <p>The University of Michigan Library has placed copies of these works online for educational and research purposes. 
                                         These works may be under copyright. If you decide to use any of these works, you are responsible for making your own 
                                         legal assessment and securing any necessary permission.</p>";

          $contact_email = "<p class='about-contact'> If you have questions about the Online Exhibit web site or 
                                the inclusion of an item in an exhibit, please contact <a href='mailto:ask-omeka@umich.edu'>ask-omeka@umich.edu</a>
                            </p>";

    echo $about_main.$about_using_images.$contact_email; 
?>
</div>
</div>
</section> 
