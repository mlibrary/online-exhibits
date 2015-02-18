<?php
  $pageTitle = __('Browse Items');
  echo head(
    array(
      'title' => $pageTitle,
      'bodyid' => 'items',
      'bodyclass' => 'items tags'
      )
  );
?>

<div id="primary">
  <h1>Browse Items</h1>
  <ul class="navigation items-nav" id="secondary-nav">
    <?php
      echo nav(array(
        array(
          'label' => 'Browse All',
          'uri' => url('items/browse')
        ),
        array(
          'label' => 'Browse by Tag',
          'uri' => url('items/tags')
        )
      ));
    ?>
  </ul>
</div>

<?php
  $tag_object = array();
  foreach (loop('tags') as $tag) {
    $tag_object[] = array(
      'name' => $tag->name,
      'uri' => url('items/browse') . '?tag=' . $tag->name
    );

    echo ($tag->name . ' ' . url('items/browse') . '?tag=' . $tag->name);
    echo('<br>');
  }

  echo foot();
?>
