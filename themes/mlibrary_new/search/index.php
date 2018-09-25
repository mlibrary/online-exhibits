<?php
    $pageTitle = __('Search Online Exhibits ') . __('(%s total)', $total_results);
    set_theme_option('display_header','0');
    echo head(array('title' => $pageTitle, 'bodyid' => 'search', 'bodyclass' => 'search'));
    $searchRecordTypes = get_search_record_types();
?>
 
    <div class="col-xs-12">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo link_to_home_page(__('Home')); ?></li>
        <li class="breadcrumb-item active">Search Results</li>
      </ol>
    </div>
    <h1>
      <?php if (isset($_GET['query'])) { $query = $_GET['query'];}
      $pageSummary =  __('%s ', $total_results) . __('Online Exhibits containing') . __(' "<span class="bold">%s</span>"', $query);
      ?> 
    </h1>
    <div class="col-xs-12">
       <h1 class="search-page--heading"><?php echo $pageSummary;?></h1>
       <div class="detail-nav-border"></div>
    </div>

    <div id="primary" class="search">
    <?php if ($total_results > 0): ?>
      <div id="exhibits" class="pretty-list">
      <?php
          $filter = new Zend_Filter_Word_CamelCaseToDash; 
          foreach (loop('search_texts') as $searchText):
              $record = get_record_by_id($searchText['record_type'], $searchText['record_id']);
              $recordType = $searchText['record_type'];
              set_current_record($recordType, $record);
       ?>
          <article>
            <div class="col-xs-12 browse-wrap">
              <?php echo '<div class="col-xs-12 col-sm-3"> <div class="img-wrap">';
                    if ($recordImage = record_image($recordType, 'square_thumbnail')):
                        echo link_to($record, 'show', $recordImage, array('class' => 'image')); 
                    endif;
                    echo '</div></div>';
                    ?>
                    <div class="col-xs-12 col-sm-9"><h2 class="item-heading">
                         <a href="<?php echo record_url($record, 'show'); ?>">
                            <?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?>
                         </a></h2>
                         <?php 
                               if($exhibitDescription = metadata('exhibit', 'description', array('snippet'=>300,'no_escape' => true))) {
                               echo '<p class="item-description">' .$exhibitDescription. '</p>';
                         }
                         $tags = str_replace(';', '', tag_string($record, '/exhibits/browse'));
                         echo '<div class="tags"><ul class="tag-list"> <li>Tags:</li>'.$tags. '</ul></div>';?>
                    </div>
            </div>
         </article>
       <?php
       endforeach;
       echo pagination_links();
    else:?>
     <div id="no-results">
      <p><?php echo __('Your query returned no results.');?></p>
     </div>
     <?php endif; ?>
</div>
<?php echo foot(); ?>
