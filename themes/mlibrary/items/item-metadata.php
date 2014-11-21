<div class="element-set">
<?php      
$elementName = $info['elementName'];
      $elementRecord = $info['element'];

      if (($info['isShowable']) && ($elementName=='Description')) {
        echo '<div id="' . text_to_id(html_escape("$setName $elementName")) . '" class="element">
                <h2>' . html_escape($elementName) . '</h2>';

        if ($info['isEmpty']) {
          echo '<div class="element-text-empty">' . $info['emptyText'] . '</div>';
        } else {
          // We need to extract the element set name from the record b/c
          // $setName contains the 'pretty' version of it that may be named differently
          // than the actual element set.
          foreach ($info['texts'] as $text){
            echo '<div class="element-text">' . $text . '</div>';
          }
        }

        echo '</div>';
      }
    }

    foreach ($elementsInSet as $info) {
      $elementName = $info['elementName'];
      $elementRecord = $info['element'];

      if ($info['isShowable']
          && $elementName != 'Title'
          && $elementName != 'Creator'
          && $elementName != 'Date'
          && $elementName != 'Identifier'
          && $elementName != 'Description') {

        echo '<div id="' . text_to_id(html_escape("$setName $elementName")) . '" class="element">' .
                '<h2>' . html_escape($elementName) . '</h2>';

        if ($info['isEmpty']) {
          echo '<div class="element-text-empty">' . $info['emptyText'] . '</div>';
        } else {
          // We need to extract the element set name from the record b/c
          // $setName contains the 'pretty' version of it that may be named differently
          // than the actual element set.
          foreach ($info['texts'] as $text) {
            echo '<div class="element-text">' . $text . '</div>';
          }
        }

        echo '</div';
      }
    }
  ?>
</div>
