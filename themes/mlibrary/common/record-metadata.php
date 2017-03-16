<?php
// you can access all the Elements type and not Dublin Core as it is used to be in Omeka 1.5, we care here about Dublin Core that is
// why the if condition is added.
foreach ($elementsForDisplay as $setName => $setElements) {
  if ($setName == 'Dublin Core') {
    $fields_in_sidebar = ['Title', 'Creator', 'Date', 'Identifier'];
    $list_elements = [];

    foreach ($setElements as $elementName => $elementInfo) {
      if (!in_array($elementName, $fields_in_sidebar)) {
        $field = '<dt>' . html_escape(__($elementName)) . '</dt>';

        foreach ($elementInfo['texts'] as $text) {
          if ($elementName == 'Source'
              && filter_var($text, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
            if (!preg_match('/^\s*https?:\/\//', $text)) { $text = 'http://' . $text; }
            $text = '<a href="' . $text . '">View Item Source</a>';
          }

          $field .= '<dd>' . $text . '</dd>';
        }

        if ($elementName == 'Description') {
          array_unshift($list_elements, $field);
        } else {
          $list_elements[] = $field;
        }
      }
    }

    echo (empty($list_elements)) ? '' : '<dl class="record-metadata-list">' . implode($list_elements, '') . '</dl>';
  }
}
?>
