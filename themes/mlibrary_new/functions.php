<?php

function public_nav_main_bootstrap() {
    $partial = array('common/menu-main-partial.phtml', 'default');
    $nav = public_nav_main();  // this looks like $this->navigation()->menu() from Zend
    $nav->setPartial($partial);
    return $nav->render();
}

function public_nav_sidebar_bootstrap() {
    $partial = array('common/menu-sidebar-partial.phtml', 'default');
    $nav = public_nav_main();  // this looks like $this->navigation()->menu() from Zend
    $nav->setPartial($partial);
    return $nav->render();
}

function public_nav_pills_bootstrap() {
    $partial = array('common/menu-item-pills-partial.phtml', 'default');
    $nav = public_nav_items();  // this looks like $this->navigation()->menu() from Zend
    $nav->setPartial($partial);
    return $nav->render();
}

function recent_items_bootstrap($recentItems,$type){
	if($type=='list'){
		return recent_items($recentItems);
		}
	elseif($type=='grid'){
	$items = get_recent_items($recentItems);
    if ($items) {
        $html = '';
        foreach ($items as $item) {
            $html .= get_view()->partial('items/grid.php', array('item' => $item));
            release_object($item);
        }
    } else {
        $html = '<p>' . __('No recent items available.') . '</p>';
    }
    return $html;
		}
}

function bs_link_logo_to_navbar($text = null)
{
    if (!$text) {
      return '<a href="' . html_escape(WEB_ROOT) . '">' . 'Online Exhibits' . '</a>' ;
    }
}

function bs_header_bg()
{
    $headerImage = get_theme_option('Header Background Image');
    if ($headerImage) {
        $storage = Zend_Registry::get('storage');
        $headerImage = $storage->getUri($storage->getPathByType($headerImage, 'theme_uploads'));
        return $headerImage;
    } else {
        return img('header-default-banner.jpg');
    }
}

function bs_header_logo()
{
    $headerImage = get_theme_option('Header Logo Image');
    if ($headerImage) {
        $storage = Zend_Registry::get('storage');
        $headerImage = $storage->getUri($storage->getPathByType($headerImage, 'theme_uploads'));
        return '<img alt="header-logo" class="img-responsive center-block" src="' . $headerImage . '" />';
    }
}
