<?php
 /**
  * Copyright (c) 2017, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
set_theme_option('display_header','0');
$title = __('Search Exhibits'). __('(%s total)', $total_results);
       echo head(
            array(
               'title' =>$title,
               'bodyid'=>'exhibit',
               'bodyclass' => 'exhibits browse'
            )
       );
    $searchRecordTypes = get_search_record_types();
?>
<div class="col-xs-12">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo link_to_home_page(__('Home')); ?></li>
        <li class="breadcrumb-item active">Search all exhibits</li>
      </ol>
  </div>
<div class="col-xs-12">
    <h1><?php echo __('%s',$total_results); echo " Online Exhibits containing";?></h1>
</div>

<div id="primary" class="browse">
    <?php if ($total_results): ?>
<div id="exhibits" class="pretty-list">
                <?php $filter = new Zend_Filter_Word_CamelCaseToDash; ?>
                <?php foreach (loop('search_texts') as $searchText): ?>
                <?php $record = get_record_by_id($searchText['record_type'], $searchText['record_id']); ?>
                <?php $recordType = $searchText['record_type']; ?>
                <?php set_current_record($recordType, $record); ?>
                <article>
                  <div class="col-xs-12 browse-wrap">
                     <?php echo '<div class="col-xs-12 col-sm-3"> <div class="img-wrap">';
                          print_r($recordType);
                      if ($recordImage = record_image($recordType, 'square_thumbnail')): ?>
                            <?php echo link_to($record, 'show', $recordImage, array('class' => 'image')); ?>
                      <?php endif; ?>
                     <?php echo '</div></div>';?>

               <?php echo strtolower($filter->filter($recordType)); ?>
                   <div class="col-xs-12 col-sm-9"><h2 class="item-heading">     
                   <a href="<?php echo record_url($record, 'show'); ?>"><?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?></a></h2>
            
                <?php  echo '</div></div>';?>
               </article>
               <?php endforeach; ?>   
    
    <?php echo pagination_links(); ?>
    <?php else: ?>
        <p><?php //echo __('Your query returned no results.');?></p>
    <?php endif; ?>
<?php echo foot(); ?>
