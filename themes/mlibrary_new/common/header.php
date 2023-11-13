<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if ( $description = option('description')): ?>
        <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>

     <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PFXF6QV');</script>
    <!-- End Google Tag Manager -->

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
        queue_css_file('universal-header');
        echo head_css();
    ?>
    <!-- Need more JavaScript files? Include them here -->
    <?php
        queue_js_file('lib/bootstrap.min');
        queue_js_file('globals');
        queue_js_file('image_viewer');
        queue_js_file('jquery-3.7.1.min');
        queue_js_file('openseadragon/openseadragon.min');
        queue_js_file('openseadragon/openseadragon-viewerinputhook.min');
        echo head_js();
    ?>
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- includes for the Design System components -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/@umich-lib/web@1.3.0/dist/umich-lib/umich-lib.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@umich-lib/components@1.1.0/dist/umich-lib/umich-lib.js"></script>

</head>
    <?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>

    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PFXF6QV"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <?php fire_plugin_hook('public_body', array('view'=>$this)); ?>
    <m-universal-header></m-universal-header>
    <div class="site-header">
     <div class="container container-medium ">
            <div class="site-header-container">
                <div class="site-heading">
                  <ul class="logo-list">
                    <li class="libary-logo"><a href="http://lib.umich.edu" class="logo-link"><img src="<?php echo img('logo.png'); ?>" alt="Link to Mlibrary website"></a></li>
                    <li class="exhibits-text"><?php echo bs_link_logo_to_navbar(); ?></li>
                  </ul>
                </div>
		<nav class="site-header-nav" role="navigation">
       <ul class="site-header-nav-list">
            <li><a href="<?php echo url('exhibits'); ?>">Browse Exhibits</a></li>
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
        <div class="col-sm-12 banner" style="background-image:linear-gradient(rgba(0,0,0,.5), rgba(0,0,0,.65)), url('<?php  echo img('header.jpg'); ?>');">
            <div id="header-claim-holder">
                <div class="container container-medium banner-container">
                     <h1 class="banner-text--large">Online Exhibits</h1>
                        <p class="banner-text--medium">Brought to you by the <a href="http://lib.umich.edu">University of Michigan Library</a></p>
                       
                </div>
            </div>
             <p class="banner-text--caption">Image: Jasper Francis Cropsey's painting of the University of Michigan campus from <a href="<?php echo url('exhibits/show/creating-a-campus/'); ?>"> Creating a Campus: A Cartographic Celebration of U-M's Bicentennial</a></p>
        </div>
    </header>
    <?php endif; ?>

    <?php if (isset($bodyid) and (get_theme_option('display_header') !== '0') and ($bodyid =='exhibit')) :?>
    <section>
    <div class="container breadcrumb-container">
    <div class="col-xs-12 col-sm-9">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo link_to_home_page(__('Home')); ?></li>
        <?php if ($bodyclass =='show') {?>
              <li class="breadcrumb-item active"><a href="<?php echo html_escape(exhibit_builder_exhibit_uri(get_current_record('exhibit')));?>">
                                                          <?php echo snippet_by_word_count(metadata('exhibit','title',array('no_escape' => true)),5,'..'); ?></a></li>
              <li class="breadcrumb-item active"><?php echo metadata('exhibit_page','title',array('no_escape' => true));?></li>
        <?php } else {?> 
              <li class="breadcrumb-item active"><?php echo metadata('exhibit','title',array('no_escape' => true));?></li><?php }?>
      </ol>
    </div>
  <div class="col-xs-12 col-sm-3">
    <ul class="share-this">
      <li>Share this Exhibit:</li>
      <li><a href="https://twitter.com/share" class="twitter-share-button" 
                                              data-text="I just saw '<?php echo( str_replace('"',"'", metadata('exhibit','title',array('no_escape' => true)))); ?>' at the U-M Library Online Exhibits!" >
                                              <span class="sr-only">Tweet</span> </a>
      </li>
    </ul>
   </div>
    </div>
    </section>
    <header
        id="banner"
        class="<?php echo get_theme_option('header_flow'); ?> page-header"
        style="background-size:cover;background-position: center center;background-image:url('<?php  echo bs_header_bg(); ?>');">

    </header>
<?php endif;?>

    <main id="content" role="main">
      <div class="container">
          <?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
