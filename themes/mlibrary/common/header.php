<!DOCTYPE html>
<html>
<head>
  <?php
    if (isset($title)) { $titleParts[] = strip_formatting($title); }
    $titleParts[] = option('site_title');
  ?>

  <title><?php echo implode(' | ', $titleParts) . ' | ' . 'MLibrary' ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="description" content="<?php echo option('description'); ?>" />
  <script src="https://api.simile-widgets.org/ajax/2.2.1/simile-ajax-api.js" type="text/javascript"></script>
  <script src=" https://api.simile-widgets.org/timeline/2.3.1/timeline-api.js?bundle=true" type="text/javascript"></script>

  <?php
    queue_css_file('screen');
    queue_css_file('jquery.fancybox');
    queue_css_file('video-js');
    queue_css_file('print');
    queue_css_file(mlibrary_get_stylesheet());

    queue_js_file('fancybox/source/jquery.fancybox');
    queue_js_file('video-js/video');
    queue_js_file('jquery.aw-showcase');

    fire_plugin_hook('public_head', array('view'=>$this));
    echo auto_discovery_link_tags();
    echo head_css();
    echo head_js();
  ?>

  <!-- this hides the slideshow divs from users who do not have javascript enabled so they don't see a big mess -->
  <noscript>
    <style> #showcase,.showcase, h2.awkward { display:none; visibility:hidden; } </style>
  </noscript>
</head>

<?php
  $GLOBALS['bodyclass'] = @$bodyclass;
  echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass));
?>

  <div id="wrap">

    <div id="logo">
      <a href="http://lib.umich.edu/">
        <img src="<?php echo img('square_mlibrary.png','images/layout'); ?>" width="61" height="60" alt="mlibrary" />
      </a>
      <a href="http://lib.umich.edu/online-exhibits/">
        <img src="<?php echo img('online-exhibits.png','images/layout'); ?>" width="286" height="33" alt="online exhibits" />
      </a>
    </div>

    <div id="primary-nav">
      <?php
        echo nav(array(
          array(
            'label' => 'Home',
            'uri' => url(''),
            'class' => 'nav-home'
          ),
          array(
            'label' => 'Browse',
            'uri' => url('exhibits'),
            'class' => 'nav-browse'
          ),
          array(
            'label' => 'Item Archive',
            'uri' => url('items'),
            'class' => 'nav-items'
          )
        ));
      ?>
  </div>

  <div id="content">
