<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */
  $pageTitle = __('Browse Items');
  $partnerId = '1038472';
  $remove = '';

  echo head(
    array(
      'title' => $pageTitle,
      'bodyid' => 'items',
      'bodyclass' => 'items browse'
      )
  );
?>

<div id="primary">
  <h1><?php echo $pageTitle;?> <?php echo __('(%s total)', $total_results); ?></h1>

  <div class="pretty-list">
    <?php foreach (loop('items') as $item): ?>
      <?php
        $has_image = metadata('item', 'has thumbnail')
                     || metadata(
                          'item',
                          array('Item Type Metadata', 'video_embeded_code_VCM'),
                          array('no_escape'=>true, 'all'=>true)
                        )
                     || metadata(
                          'item',
                          array('Item Type Metadata', 'Video_embeded_code'),
                          array('no_escape' => true, 'all' => true)
                        );
        $has_tags = metadata('item', 'has tags');
        $Creator = metadata('item', array('Dublin Core', 'Creator'));
        $Date = metadata('item', array('Dublin Core', 'Date'));
      ?>
      <article class="cf<?php
        if (!$has_image) { echo ' no_image'; }
        if (!$has_tags) { echo ' no_tags'; }
      ?>">
        <div class="item-body">
          <h2 class="item-heading">
            <?php
              echo mlibrary_link_to_item_with_return(
                   strip_formatting(
                      metadata(
                        'item',
                        array('Dublin Core', 'Title')
                      )
                   ),
                   array('class'=>'permalink')
              );
            ?>
          </h2>

          <?php
            if ($has_image) { echo '<div class="img-wrap">'; }

            if (metadata('item', 'has thumbnail')) {

              echo mlibrary_link_to_item_with_return(
                item_image(
                  'square_thumbnail',
                  array(
                    'alt' => strip_formatting(
                      metadata(
                      'item',
                      array('Dublin Core', 'Title')
                    ))
                  )
                )
              );

            } elseif ($elementvideos_VCM = metadata(
                                            'item',
                                            array('Item Type Metadata', 'video_embeded_code_VCM'),
                                            array('no_escape'=>true, 'all'=>true)
                                          )) {
              $data = $elementvideos_VCM[0];
              preg_match('/\/entry_id\/([a-zA-Z0-9\_]*)?/i', $data, $match);
              $entry_id = $match[1];

              echo mlibrary_link_to_item_with_return('<img src="http://cdn.kaltura.com/p/' . $partnerId . '/thumbnail/entry_id/' . $match[1] . '/width/200/height/200/type/1/quality/100" / style="width:200px; height:200px">');

            } elseif ($elementvideos = metadata(
                                        'item',
                                        array('Item Type Metadata', 'Video_embeded_code'),
                                        array('no_escape' => true, 'all' => true)
                                      )) {
              $videoid = str_replace($remove, "", $elementvideos);
              $image = "<img src='http://i4.ytimg.com/vi/" . $videoid[0] . "/default.jpg' style='width:200px; height:200px'/>";
              echo mlibrary_link_to_item_with_return($image);
            }

            if ($has_image) { echo '</div>'; }
            // if condition added to display metadata if it is available.
            if ((!empty($Creator)) || (!empty($Date))) {
              $metadata = [
                'Created by'             => metadata('item', array('Dublin Core', 'Creator')),
                'Date'                   => metadata('item', array('Dublin Core', 'Date')),
                'Additional Information' => metadata(
                  'item',
                  array('Item Type Metadata', 'Text'),
                  array('snippet' => 250)
                )
              ];
              echo '<dl>';

              foreach ($metadata as $label => $info) {
                if ($info) { echo '<dt>' . $label . ' </dt> <dd>' . $info . '</dd>'; }
              }

              echo '</dl>';
            }

          ?>
        </div>

        <?php
          if ($has_tags) {
            echo '<div class="tags"> <h4 class="tags-heading">Tags</h4> ' .
                    str_replace(';', '', tag_string('items')) .
                 '</div>';
          }
        ?>

        <?php fire_plugin_hook('public_items_browse_each', array('view' => $this, 'item' => $item)); ?>
      </article>
    <?php endforeach; ?>

    <?php
      echo pagination_links();
      fire_plugin_hook('public_items_browse', array('items'=>$items, 'view' => $this));
    ?>

  </div>
</div>

<?php echo foot(); ?>
