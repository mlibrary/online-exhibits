<!DOCTYPE html>
<html>
<head>
<title><?php echo $title ? $title.' | ' : '';
echo settings('site_title'); 
echo ' | '.'MLibrary' ?></title>

<!-- Meta -->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php echo settings('description'); ?>" />

<?php echo auto_discovery_link_tag(); ?>

<!-- Get Core stylesheets -->

<?php echo queue_css('screen');
queue_css('jquery.fancybox-1.3.4');
queue_css('video-js');
queue_css('print'); 
display_css();
?>

<!-- Get the Configurable stylesheet -->

<link rel="stylesheet" media="screen" href="<?php echo html_escape(css(mlibrary_get_stylesheet())); ?>" />

<!-- JavaScripts -->
 <script src="https://api.simile-widgets.org/ajax/2.2.1/simile-ajax-api.js" type="text/javascript"></script>
<script src=" https://api.simile-widgets.org/timeline/2.3.1/timeline-api.js?bundle=true" type="text/javascript"></scripts>

<?php echo js('default'); ?>
<?php display_js();?>
<!-- start Conditional JS -->

<!-- the following scripts only load on the homepage and items pages due to potential plugin conflicts (incl. current version of MyOmeka).  If you would like to use the slideshow or fancybox on another page, add the bodyid for each page below, separated by the or operator (||) -->

	<?php if ($bodyid==("home"||"items")){
	echo js('fancybox/jquery.fancybox-1.3.4');
	echo js('fancybox/jquery.easing-1.3.pack');
	echo js('video-js/video');
	echo js('jquery.aw-showcase');
	}?>


<!-- end Conditional JS -->

<!-- make bodyclass available throughout page  -->
<?php $GLOBALS['bodyclass'] = $bodyclass ?>

<!-- Plugin Stuff -->

<?php echo plugin_header(); ?>

<!-- this hides the slideshow divs from users who do not have javascript enabled so they don't see a big mess -->
<noscript>
<style>#showcase,.showcase, h2.awkward{display:none; visibility:hidden;}</style>
</noscript>

</head>



<body<?php echo $bodyid ? ' id="'.$bodyid.'"' : ''; ?><?php echo $bodyclass ? ' class="'.$bodyclass.'"' : ''; ?>>
	
	<div id="wrap">
		<div id="logo">
		<a href="http://lib.umich.edu/"><img src="<?php echo img('mlibrary_logo.jpg','images/layout'); ?>" width="102px" height="24px" alt="mlibrary" /></a>
		<a href="http://lib.umich.edu/online-exhibits/"><img src="<?php echo img('online-exhibits.jpg','images/layout'); ?>" width="102px" height="24px" alt="online exhibits" /></a>
		</div>
		<div id="primary-nav">
        	
			<ul class="navigation <?php $urlparts = explode("/",$_SERVER['REQUEST_URI']); if (in_array('galleries',$urlparts)){ echo 'galleries'; }; ?>"> 
			<?php echo public_nav(array('Home'=>uri(''),'Browse'=>uri('exhibits'),'Galleries'=>uri('exhibits/show/galleries'),'Item Archive'=>uri('items'),'Time Line'=>uri('neatline-time/timelines'))); ?>
            <li class="umLogo no-tab"><a href="http://www.umich.edu"><img src="<?php echo img('umLogo.gif','images/layout'); ?>" alt="University of Michigan" width="143" height="18"/></a>
			</ul>
		</div><!-- end primary-nav -->
		<div id="content">