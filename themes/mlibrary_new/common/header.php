<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if ( $description = option('description')): ?>
        <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>

    <!-- Will build the page <title> -->
    <?php
        if (isset($title)) { $titleParts[] = strip_formatting($title); }
        $titleParts[] = option('site_title');
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>
    <?php echo auto_discovery_link_tags(); ?>

    <!-- Will fire plugins that need to include their own files in <head> -->
    <?php fire_plugin_hook('public_head', array('view'=>$this)); ?>


    <!-- Need to add custom and third-party CSS files? Include them here -->
    <?php
        queue_css_url('//fonts.googleapis.com/css?family=Open+Sans:400,600');
		$bootswatch_theme=get_theme_option('Style Sheet');
        queue_css_file($bootswatch_theme.'/bootstrap');
        queue_css_file($bootswatch_theme.'/falafel');
        queue_css_file('style');
        echo head_css();
    ?>

    <!-- Need more JavaScript files? Include them here -->
    <?php
        queue_js_file('lib/bootstrap.min');
        queue_js_file('globals');
        echo head_js();
    ?>
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
    <?php fire_plugin_hook('public_body', array('view'=>$this)); ?>
    <div class="site-header">
        <div class="container container-medium site-container">
            <nav >
                <div class="site-heading" >
                  <?php echo bs_link_logo_to_navbar(); ?>
                </div>
            </nav>
        </div>
    </div>
    <div class="search-panel">
        <div class="form-group search-box container container-medium">
         <?php echo search_form(array('show_advanced' => false, 'form_attributes'=>array('id'=>'navbar-search', 'class'=>'form-inline'))); ?>
        </div>
    </div>

<?php if (((get_theme_option('display_header') !== '0')) and (end(explode('/',$_SERVER['REQUEST_URI']))!='about')) : ?>   
     <header id="banner" class="<?php echo get_theme_option('header_flow'); ?> page-header" style="background-size:cover;background-image:url('<?php 
		if ((get_theme_option('Header Background Image') === null)){
			echo img('defaulthbg.jpg');
		}			
		else echo bs_header_bg(); 
		?>');">
		<div class="row header-row">
			<?php if ((get_theme_option('header_logo_image') !== null)): ?>
			<div class="col-md-2 col-md-offset-1" id="header-logo-holder">
				 <?php echo bs_header_logo(); ?>
			</div>
			<?php endif; ?>
			<div id="header-claim-holder">
				<div>
				<?php if ((get_theme_option('header_image_heading') !== '')): ?>
					<h1><?php echo get_theme_option('header_image_heading'); ?></h1>
				<?php endif; ?>
				<?php if ((get_theme_option('header_image_text') !== '')): ?>
					<p><?php echo get_theme_option('header_image_text'); ?></p>
				<?php endif; ?>
				</div>			
			</div>
		</div>
    </header> 
    <?php endif; ?>  
    
    <main id="content">
      <div class="container">
          <?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
