<style>
    :root {
        --background-color: <?php echo (get_theme_option('background_color')) ? get_theme_option('background_color') : "#FFFFFF"; ?>;
        --text-color: <?php echo (get_theme_option('text_color')) ? get_theme_option('text_color') : "#393939"; ?>;
        --link-color: <?php echo (get_theme_option('link_color')) ? get_theme_option('link_color') : "#6c6c6c"; ?>;
        --button-color: <?php echo (get_theme_option('button_color')) ? get_theme_option('button_color') : "#000000"; ?>;
        --button-text-color: <?php echo (get_theme_option('button_text_color')) ? get_theme_option('button_text_color') : "#FFFFFF"; ?>;
        --title-color:  <?php echo (get_theme_option('header_title_color')) ? get_theme_option('header_title_color') : "#000000"; ?>;
        --thumbnail-size: <?php echo (get_theme_option('use_original_thumbnail_size') == '1') ? 'auto' : '100px'; ?>;
    }

    body {
        background-color: var(--background-color);
        color: var(--text-color);
    }
    #site-title a:link, #site-title a:visited,
    #site-title a:active, #site-title a:hover {
        color: var(--title-color);
        <?php if (get_theme_option('header_background')): ?>
        text-shadow: 0px 0px 20px #000;
        <?php endif; ?>
    }
    a {
        color: var(--link-color);
    }

    .button, button,
    input[type="reset"],
    input[type="submit"],
    input[type="button"],
    .pagination_next a,
    .pagination_previous a {
        background-color: var(--button-color);
        color: var(--button-text-color);
    }

    #search-form input[type="text"] {
        border-color: var(--button-color)
    }

    .record img,
    .browse .item-img,
    .browse .image,
    .browse #content .item img,
    .browse .item #content img,
    .browse .image img,
    #recent-items img.image {
        height: var(--thumbnail-size);
    }

    @media (max-width:768px) {
        #primary-nav li {
            background-color: var(--button-color);
        }

        #primary-nav ul.navigation .sub-nav-toggle:after,
        #primary-nav li a {
            color: var(--button-text-color);
        }
    }
</style>