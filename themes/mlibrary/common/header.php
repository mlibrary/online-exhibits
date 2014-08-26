<!DOCTYPE html>
<html>
<head>
 <?php
     if (isset($title)) {
        $titleParts[] = strip_formatting($title);
//         $titleParts[] = $title;
    }
    $titleParts[] = option('site_title');
   
    
    ?>
    
<title><?php echo implode(' | ', $titleParts).' | ';
echo 'MLibrary' ?></title>

<!-- Meta -->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php echo option('description'); ?>" />

<?php echo auto_discovery_link_tags(); ?>

<!-- Get Core stylesheets -->
  <!-- Plugin Stuff -->
    <?php fire_plugin_hook('public_head', array('view'=>$this)); ?>
    
    
<?php echo queue_css_file('screen');
echo queue_css_file('jquery.fancybox');
queue_css_file('video-js');
queue_css_file('print'); 
echo head_css();
?>

<!-- Get the Configurable stylesheet -->

<link rel="stylesheet" media="screen" href="<?php echo html_escape(css_src(mlibrary_get_stylesheet())); ?>" />

<!-- JavaScripts -->
<script src="https://api.simile-widgets.org/ajax/2.2.1/simile-ajax-api.js" type="text/javascript"></script>
<script src=" https://api.simile-widgets.org/timeline/2.3.1/timeline-api.js?bundle=true" type="text/javascript"></script>

<?php //echo js_tag('default'); ?>

<!-- start Conditional JS -->

<!-- the following scripts only load on the homepage and items pages due to potential plugin conflicts (incl. current version of MyOmeka).  If you would like to use the slideshow or fancybox on another page, add the bodyid for each page below, separated by the or operator (||) -->

	<?php //if ($bodyid==("home"||"items")){
	//echo 
	queue_js_file('fancybox/source/jquery.fancybox');
	//queue_js_file('fancybox/lib/jquery.mousewheel-3.0.6.pack');
	
	//queue_js_file('fancybox/source/helpers/jquery.fancybox-buttons');
//	queue_js_file('fancybox/jquery.easing-1.3.pack');
	queue_js_file('video-js/video');
	queue_js_file('jquery.aw-showcase');
	//queue_js_file('globals'); 
	//}?>
<?php echo head_js();?>

<!-- end Conditional JS -->

<!-- make bodyclass available throughout page  -->
<?php $GLOBALS['bodyclass'] = @$bodyclass; ?>

<!-- Plugin Stuff -->

<?php //echo plugin_header(); ?>

<!-- this hides the slideshow divs from users who do not have javascript enabled so they don't see a big mess -->
<noscript>
<style>#showcase,.showcase, h2.awkward{display:none; visibility:hidden;}</style>
</noscript>

</head>



<!--omeka1.5<body<?php echo $bodyid ? ' id="'.$bodyid.'"' : ''; ?><?php echo $bodyclass ? ' class="'.$bodyclass.'"' : ''; ?>>-->
 <?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>

	<div id="wrap">
		<div id="logo">
		<a href="http://lib.umich.edu/"><img src="<?php echo img('mlibrary_logo.jpg','images/layout'); ?>" width="102px" height="24px" alt="mlibrary" /></a>
		<a href="http://lib.umich.edu/online-exhibits/"><img src="<?php echo img('online-exhibits.jpg','images/layout'); ?>" width="102px" height="24px" alt="online exhibits" /></a>
		</div> <!--log-->
		<div id="primary-nav">
<!--  Omeka1.5 <ul class="navigation <?php $urlparts = explode("/",$_SERVER['REQUEST_URI']); if (in_array('galleries',$urlparts)){ echo 'galleries'; }; ?>"> -->
     <ul class="navigation">
			  <?php $urlparts = explode("/",$_SERVER['REQUEST_URI']); if (in_array('galleries',$urlparts)){ echo 'galleries'; }; ?>
			  <?php //Omeka 1.5 echo public_nav(array('Home'=>uri(''),'Browse'=>uri('exhibits'),'Galleries'=>uri('exhibits/show/galleries'),'Item Archive'=>uri('items'),'Time Line'=>uri('neatline-time/timelines'))); 
				echo nav(array(array('label'=>'Home','uri'=>url(''),'class'=>'nav-home'),array('label'=>'Browse','uri'=>url('exhibits'),'class'=>'nav-browse'),array('label'=>'Item Archive','uri'=>url('items'),'class'=>'nav-items')));?>
		
				 
            <li class="umLogo no-tab"><a href="http://www.umich.edu"><img src="<?php echo img('umLogo.gif','images/layout'); ?>" alt="University of Michigan" width="143" height="18"/></a>
		 </ul> <!-- navigation-->
		</div><!-- end primary-nav -->	
	<div id="content">
