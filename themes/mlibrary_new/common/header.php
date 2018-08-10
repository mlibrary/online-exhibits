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
        queue_js_file('openseadragon/openseadragon.min');
        queue_js_file('openseadragon/openseadragon-viewerinputhook.min');
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
     <div class="container container-medium ">
            <div class="site-header-container">
                <div class="site-heading">
                  <ul class="logo-list">
                    <li class="libary-logo"><a href="http://lib.umich.edu" class="logo-link"><img src="<?php echo img('logo.png'); ?>" alt="Link to Mlibrary website"></a></li>
                    <li class="exhibits-text"><?php echo bs_link_logo_to_navbar(); ?></li>
                    
                  </ul>
                </div>
		<nav class="site-header-nav">
                  <ul class="site-header-nav-list">
                      <li><a href="<?php echo url('exhibits'); ?>">Browse Exhibits</a></li>
                      <li><a href="<?php echo url('about'); ?>">About</a></li>
                  </ul>
		</nav>
            </div>
        </div>
    </div>

    <div class="search-panel">
        <div class="form-group search-box container container-medium">
         <?php echo search_form(array('show_advanced' => false, 'form_attributes'=>array('id'=>'navbar-search', 'class'=>'form-inline'))); ?>
        </div>
    </div>

    <?php if (isset($bodyid) and ($bodyid === 'home')): ?>
    <header>
        <div class="col-sm-12 banner" style="background-image:url('<?php  echo img('header.jpg'); ?>');">
            <div id="header-claim-holder">
                <div class="container container-medium">
                     <h1 class="banner-text--large">Online Exhibits</h1>
                        <p class="banner-text--medium">Brought to you by the <a href="http://lib.umich.edu">University of Michigan Library</a></p>
                        <p class="banner-text--caption">Image: Rotunda Reading Room of the Old General Library from <a href="<?php echo url('exhibits/show/library-bicentennial'); ?>"> Stories of the University Library: A Bicentennial Exhibit</a></p>
                </div>
            </div>
        </div>
    </header>
    <?php endif; ?>

    <?php if (isset($bodyid) and (get_theme_option('display_header') !== '0') and ($bodyid !== 'home')) : ?>
    <header
        id="banner"
        class="<?php echo get_theme_option('header_flow'); ?> page-header"
        style="background-size:cover;background-image:url('<?php  echo bs_header_bg(); ?>');">

    </header>
    <?php endif; ?>

    <main id="content">
      <div class="container">
          <?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
