<div class="gallery-custom">

  <div class="primary">
    <h3 class="FileCaption"></h3>
    <div class="fullsizeitemimage"></div>

    <div class="multi" id="tab">
      <span class="button" id="viewinarchive">View Item</span>
    </div>

    <div class="Metadata">
      <ul>
        <li id="creator"></li>
        <li id="date"></li>
      </ul>
      <p></p>
    </div>
  </div>

  <div class="secondary gallery">
    <div class="custom_layout">
      <?php
        echo exhibit_builder_thumbnail_gallery(1, 12, array('class'=>'permalink'));
        if ($text = exhibit_builder_page_text(1)) {
          echo '<div class="exhibit-text">' . $text . '</div>';
        }
      ?>
    </div>
  </div>

</div>

  <script>
    <?php
      $itemsobj = array();

      for ($i = 1; $i <= 12; $i++) {
        $item_array = exhibit_builder_page_attachment($i);

        if (!empty($item_array)) {
          $json_fullsize = exhibit_builder_attachment_markup(
            $item_array,
            array(
              'imageSize' => 'fullsize',
              'imageorder' => $i,
              'title' => metadata(
                $item_array['item'],
                array('Dublin Core', 'Title')
              ),
              'creator' => metadata(
                $item_array['item'],
                array('Dublin Core', 'Creator')
              ),
              'year' => metadata(
                $item_array['item'],
                array('Dublin Core', 'Date')
              ),
              'description' => metadata(
                $item_array['item'],
                array('Dublin Core', 'Description')
              )
            ),
            array('class' => 'permalink')
          );

         $itemsobj = array_merge($itemsobj, $json_fullsize);
        }
      }
    ?>

    gallery_items = <?php echo json_encode($itemsobj); ?>;
    exhibitPath = "<?php echo exhibit_builder_exhibit_uri(get_current_record('exhibit', false)); ?>";

    jQuery(document).ready(function() {
      if (jQuery(".square_thumbnail.first div").length > 0) {
        renderImageDisplay(jQuery(".square_thumbnail.first"));
      }

      jQuery(".square_thumbnail").click(function(){
        renderImageDisplay(this);
      });
    });

    function renderImageDisplay(thumbnail) {
      var item = gallery_items[jQuery(thumbnail).attr('file_id')];

      // Array of all the metadata that can be updated.
      // Each item is structured as follows:
      //   [ 'Selector for the field', 'New content for the field' ]
      metadata = [
        [ ".fullsizeitemimage", item.image ],
        [ ".FileCaption", item.title ],
        [ ".Metadata p", item.description ],
        [ ".Metadata #date", item.date ],
        [ ".Metadata #creator", item.creator ],
        [ ".multi #viewinarchive",
          '<a href="' + exhibitPath + '/item/' + item.archive + '">View Item </a>' ]
      ];

      // Update each metadata field. If no content is set, hide the field.
      jQuery.each(metadata, function(index, field_data) {
        if (field_data[1]) {
          jQuery(field_data[0]).html(field_data[1]).show();
        } else {
          jQuery(field_data[0]).html('').hide();
        }
      });

      jQuery("a.fancyitem").fancybox(jQuery(document).data('fboxSettings'));
    }
  </script>
