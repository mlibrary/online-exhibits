<?php
 /**
  * Copyright (c) 2016, Regents of the University of Michigan.
  * All rights reserved. See LICENSE.txt for details.
  */?>

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
  <!--<script src="http://api.simile-widgets.org/ajax/2.2.1/simile-ajax-api.js" type="text/javascript"></script>
  <script src="http://api.simile-widgets.org/timeline/2.3.1/timeline-api.js?bundle=true" type="text/javascript"></script>
  <script src="http://cdn.leafletjs.com/leaflet-0.5.1/leaflet.js"></script> -->
 <!-- <script type="text/javascript" src="//online-exhibits-2.3/themes/mlibrary/javascripts/L.TileLayer.Zoomify.js"></script>--> 

  <?php
    fire_plugin_hook('public_head', array('view'=>$this));

    queue_css_file('screen');
    queue_css_file('jquery.fancybox');
    queue_css_file('video-js');
    queue_css_file('print');
    queue_css_file(mlibrary_get_stylesheet());

    queue_js_file('fancybox/source/jquery.fancybox');
    queue_js_file('video-js/video');
    queue_js_file('html5shiv-printshiv.min', 'javascripts', array('conditional' => '(lt IE 9)'));
    queue_js_file('JwPlayer/jwplayer');
    queue_js_file('fancybox/source/fancybox-init-config');
    queue_js_file('jquery.aw-showcase.min');
    queue_js_file('openseadragon/openseadragon.min');
    queue_js_file('openseadragon/openseadragon-viewerinputhook.min');
    echo auto_discovery_link_tags();
    echo head_css();
    echo head_js('L.TileLayer.Zoomify');
  ?>

</head>
<?php
  $GLOBALS['bodyclass'] = @$bodyclass;
  echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass));
?>
  <header>
    <div class="wrap">

      <div id="logo">
        <a href="http://lib.umich.edu/">
          <img src="<?php echo img('square_mlibrary.png','images/layout'); ?>" width="61" height="60" alt="University of Michigan Library" />
        </a>
        <a href="<?php echo url('') ?>">
          <img src="<?php echo img('online-exhibits.png','images/layout'); ?>" width="286" height="33" alt="Online Exhibits" />
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
    </div>
  </header>

  <div id="content">
    <div class="wrap cf">
