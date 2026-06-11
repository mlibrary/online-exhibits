<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if ($author = option('author')): ?>
    <meta name="author" content="<?php echo $author; ?>" />
    <?php endif; ?>
    <?php if ($copyright = option('copyright')): ?>
    <meta name="copyright" content="<?php echo $copyright; ?>" />
    <?php endif; ?>
    <?php if ( $description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>
    <?php
    if (isset($title)) {
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <!-- Plugin Stuff -->

    <?php fire_plugin_hook('public_head', array('view'=>$this)); ?>


    <!-- Stylesheets -->
    <?php
    queue_css_file(array('iconfonts','public','style'));
    queue_css_url('//fonts.googleapis.com/css?family=PT+Serif:400,700,400italic,700italic');
    echo head_css();

    echo theme_header_background();
    ?>
    <?php echo $this->partial('common/theme-config.php'); ?>
    <!-- JavaScripts -->
    <?php
    queue_js_file('vendor/selectivizr', 'javascripts', array('conditional' => '(gte IE 6)&(lte IE 8)'));
    queue_js_file('vendor/respond');
    queue_js_file('vendor/jquery-accessibleMegaMenu');
    queue_js_file('globals');
    queue_js_file('default');
    echo head_js();
    ?>
</head>
<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
    
    <div id="skipnav">
        <span class="spacer">&nbsp;</span>
        <a href="#content"><?php echo __('Skip to main content'); ?></a>
    </div>
    <?php fire_plugin_hook('public_body', array('view'=>$this)); ?>

        <header role="banner">
            <?php fire_plugin_hook('public_header', array('view'=>$this)); ?>
            <div id="site-title"><?php echo link_to_home_page(theme_logo()); ?></div>
        </header>

        <div id="wrap">
            <button type="button" class="menu-button button" aria-expanded="false" aria-controls="primary-nav"><?php echo __('Menu'); ?> <span class="menu-icon" aria-hidden="true"></span></button>
            <nav id="primary-nav" role="navigation" aria-label="Main navigation">
                <?php echo public_nav_main(array('role' => 'navigation')); ?>
                <div id="search-container" role="search">
                    <?php 
                        $showAdvanced = get_theme_option('use_advanced_search');
                        $formClass = ($showAdvanced) ? 'with-advanced' : '';
                        echo search_form(array('show_advanced' => $showAdvanced, 'form_attributes' => array('class' => $formClass))); 
                    ?>
                </div>
            </nav>
            <div id="content" role="main" tabindex="-1">
                <?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
